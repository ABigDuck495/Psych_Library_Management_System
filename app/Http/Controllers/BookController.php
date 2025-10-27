<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $books = Book::with(['authors', 'category', 'copies'])->paginate(10);
        return view('books.index', compact('books'));
    }

    public function userInterface()
    {
        $books = Book::with(['authors', 'category'])->get();
        return view('userInterface.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::pluck('category_name', 'id');
        $authors = Author::all();
        return view('books.create', compact('categories', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'copies_count' => 'nullable|integer|min:1',
            'authors' => 'required|array|min:1',
            'year_published' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'authors.*.first_name' => 'required|string|max:255',
            'authors.*.last_name' => 'required|string|max:255',
            'authors.*.middle_name' => 'nullable|string|max:255',
            'authors.*.appellation' => 'nullable|string|max:255',
            'authors.*.extension' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $copiesCount = $validated['copies_count'] ?? 1;

            // Create the book
            $book = Book::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'year_published' => $validated['year_published'],
                'category_id' => $validated['category_id'],
            ]);

            // Create book copies
            for ($i = 0; $i < $copiesCount; $i++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'is_available' => true,
                ]);
            }

            // Attach authors
            foreach ($validated['authors'] as $authorData) {
                $author = Author::firstOrCreate([
                    'first_name' => $authorData['first_name'],
                    'last_name' => $authorData['last_name'],
                ]);
                $book->authors()->attach($author->id);
            }
        });

        return redirect()->route('books.index')->with('success', 'Book created successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['authors', 'category', 'copies']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::pluck('category_name', 'id');
        $authors = Author::all();
        $copies_count = $book->copies()->count();
        return view('books.update', compact('book', 'categories', 'authors', 'copies_count'));
    }

     public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'authors' => 'sometimes|array|min:1',
            'authors.*.first_name' => 'required_with:authors|string|max:255',
            'authors.*.last_name' => 'required_with:authors|string|max:255',
            'authors.*.middle_name' => 'nullable|string|max:255',
            'authors.*.appellation' => 'nullable|string|max:255',
            'authors.*.extension' => 'nullable|string|max:255',
            'copies_count' => 'sometimes|integer|min:1'
        ]);

        DB::transaction(function () use ($validated, $book, $request) {
            // Update basic fields
            $book->update(
                collect($validated)->only(['title', 'description', 'year_published', 'category_id'])->toArray()
            );

            // Sync authors if provided
            if ($request->has('author_ids')) {
                $book->authors()->sync($validated['author_ids'] ?? []);
            }

            // Update copies count
            if ($request->filled('copies_count')) {
                $desired = (int)$request->input('copies_count');
                $existing = $book->copies()->count();

                if ($desired > $existing) {
                    for ($i = 0; $i < ($desired - $existing); $i++) {
                        BookCopy::create([
                            'book_id' => $book->id,
                            'is_available' => true
                        ]);
                    }
                } elseif ($desired < $existing) {
                    // Only delete available copies that aren't currently borrowed
                    $availableCopies = $book->copies()
                        ->where('is_available', true)
                        ->whereDoesntHave('transactions', function($query) {
                            $query->whereIn('transaction_status', ['requested', 'approved', 'borrowed']);
                        })
                        ->limit($existing - $desired)
                        ->get();

                    foreach ($availableCopies as $copy) {
                        $copy->delete();
                    }
                }
            }
        });

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }


    public function destroy(Book $book)
    {
        DB::transaction(function () use ($book) {
            // Delete copies first
            $book->copies()->delete();
            // Detach authors
            $book->authors()->detach();
            // Delete book
            $book->delete();
        });

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    public function canBeRequested(Book $book)
    {
        return $book->copies()->where('is_available', true)->exists();
    }

    public function availableCopies($bookId)
    {
        return BookCopy::where('book_id', $bookId)
                      ->where('is_available', true)
                      ->count();
    }

    public function addInventory()
    {
        $categories = Category::pluck('category_name', 'id');
        $authors = Author::all();

        if (view()->exists('catalogue.addInventory')) {
            return view('catalogue.addInventory', compact('categories', 'authors'));
        }

        return view('books.create', compact('categories', 'authors'));
    }
}