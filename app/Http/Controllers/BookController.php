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
            'SKU' => 'required|unique:books',
            'title' => 'required',
            'year_published' => 'required|digits:4',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'publisher' => 'nullable',
            'isbn' => 'nullable|unique:books',
            'pages' => 'nullable|integer',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id',
            'copies_count' => 'required|integer|min:1|max:20' // Limit to reasonable number
        ]);

        // Create the book
        $book = Book::create($validated);
        
        // Attach authors
        $book->authors()->attach($request->author_ids);
        
        // Create book copies
        for ($i = 0; $i < $request->copies_count; $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'status' => 'available'
            ]);
        }

    return redirect()->route('books.show', $book)
                    ->with('success', 'Book and ' . $request->copies_count . ' copies created successfully!');
}

    public function show(Book $book)
    {
        $book->load(['authors', 'category']);
        return view('books.show', compact('book'));
    }

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
}