<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Author;
use App\Models\BookAuthor;
use App\Models\Thesis;
use App\Models\ThesisAuthor;
use App\Models\ThesisDept;

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
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Create or find the author
        $author = Author::firstOrCreate([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
        ]);

        // Create the book
        $book = Book::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'year_published' => $validated['year_published'],
            'category_id' => $validated['category_id'],
        ]);

        // Attach author to book
        $book->authors()->attach($author->id);

        return redirect()->route('books.index')->with('success', 'Book created successfully!');
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