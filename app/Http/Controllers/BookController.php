<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\BookCopy;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['authors', 'category'])->get();
        return view('books.index', compact('books'));
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

        // Create the thesis record
        $book = Book::create($validated);

        // Link thesis to authors
        $book->authors()->sync($authorIds);
        
        // Create book copies
        for ($i = 0; $i < $request->copies_count; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'is_Available' => true
            ]);
        }

    return redirect()->route('books.index', $book)
                    ->with('success', 'Book and ' . $request->copies_count . ' copies created successfully!');
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
}