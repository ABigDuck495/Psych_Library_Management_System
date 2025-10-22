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
        // Validate the book data
        $validated = $request->validate([
            'title' => 'required',
            'year_published' => 'required|digits:4',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            // 'publisher' => 'nullable',
            // 'isbn' => 'nullable|unique:books',
            // 'pages' => 'nullable|integer',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id',
            'copies_count' => 'required|integer'
        ]);

        // Create the book
        $authorIds = $validated['author_ids'] ?? [];
        unset($validated['author_ids']);

        $copiesCount = $validated['copies_count'] ?? $request->input('copies_count', 1);

        $book = Book::create($validated);

        // Link book to authors
        $book->authors()->sync($authorIds);

        // Create book copies (use correct column name 'is_available')
        for ($i = 0; $i < (int)$copiesCount; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'is_available' => true
            ]);
        }

        return redirect()->route('books.index')
                        ->with('success', 'Book and ' . $copiesCount . ' copies created successfully!');
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
        return view('books.update', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
        ]);

        $book->update($validated);

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