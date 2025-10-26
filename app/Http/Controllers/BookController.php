<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class BookController extends Controller
{
    public function __construct()
    {
        // Require authentication for all book routes (viewing, creating, etc.)
        $this->middleware('auth');
    }
    public function index()
    {
        $books = Book::with(['authors', 'category'])->get();
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
        // ✅ Validate form data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'copies_count' => 'nullable|integer|min:1',
            'authors' => 'required|array|min:1',
            'year_published' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'authors.*.first_name' => 'required|string|max:255',
            'authors.*.last_name' => 'required|string|max:255',
        ]);

        // ✅ Set number of copies (default to 1 if empty)
        $copiesCount = $validated['copies_count'] ?? 1;

        // ✅ Create the book
        $book = Book::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'year_published' => $validated['year_published'],
            'category_id' => $validated['category_id'],
        ]);

        // ✅ Create the specified number of book copies
        for ($i = 0; $i < $copiesCount; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'is_available' => true,
            ]);
        }

        // ✅ Attach authors
        foreach ($validated['authors'] as $authorData) {
            $author = Author::firstOrCreate([
                'first_name' => $authorData['first_name'],
                'last_name' => $authorData['last_name'],
            ]);
            $book->authors()->attach($author->id);
        }

        return redirect()->route('books.index')->with('success', 'Book created successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['authors', 'category', 'copies']);
        return view('books.show', compact('book'));
    }
// 
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
            'author_ids' => 'sometimes|array',
            'author_ids.*' => 'exists:authors,id',
            'copies_count' => 'sometimes|integer|min:1'
        ]);

        // Update basic fields
        $book->update(
            collect($validated)->only(['title', 'description', 'year_published', 'category_id'])->toArray()
        );

        // Sync authors if provided
        if ($request->has('author_ids')) {
            $book->authors()->sync($validated['author_ids'] ?? []);
        }

        // If copies_count provided, add copies if the requested count is greater than existing
        if ($request->filled('copies_count')) {
            $desired = (int)$request->input('copies_count');
            $existing = $book->copies()->count();
            if ($desired > $existing) {
                $toCreate = $desired - $existing;
                for ($i = 0; $i < $toCreate; $i++) {
                    BookCopy::create([
                        'book_id' => $book->id,
                        'is_available' => true
                    ]);
                }
            }
        }

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $book->authors()->detach();
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
    public function canBeRequested(Book $book)
    {
        $availableCopies = $book->copies()->where('is_available', true)->count();
        return $availableCopies > 0;
    }
    public function availableCopies($thesisId){
        // Return number of available copies for a given book id
        $availableCopiesCount = BookCopy::where('book_id', $thesisId)
                                        ->where('is_available', true)
                                        ->count();
        return $availableCopiesCount;
    }
    /**
     * Fallback view used in several places in the app to add inventory.
     * Some older views expect 'catalogue.addInventory' or 'books.addInventory'.
     * Provide categories and authors so those blades won't error.
     */
    public function addInventory()
    {
        $categories = Category::pluck('category_name', 'id');
        $authors = Author::all();

        // Prefer catalogue.addInventory if it exists, otherwise fall back to books.create
        if (view()->exists('catalogue.addInventory')) {
            return view('catalogue.addInventory', compact('categories', 'authors'));
        }

        return view('books.create', compact('categories', 'authors'));
    }
    public function request(Request $request, Book $book){
        $user = auth()->user();
        if(!$user){
            return view('auth.login');
        }

        $availableCopy = $book->getNextAvailableCopy();
        if(!$availableCopy){
            return redirect()->back()->with('error', 'No available copies found for this book.');
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $availableCopy->id,
            'copy_type' => get_class($availableCopy),
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        // mark copy unavailable so others can't request it
        $availableCopy->is_available = false;
        $availableCopy->save();

        return redirect()->back()->with('success', 'Book request submitted successfully. Please wait for approval.');
    }
}