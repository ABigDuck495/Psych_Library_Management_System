<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource - READ (like books.php)
     */
    public function index()
    {
        $books = Book::all(); // SELECT * FROM books
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource - CREATE form (like create_book.php)
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource - CREATE handling (like create_book.php form action)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'description' => 'nullable',
            'published_year' => 'required|integer',
        ]);

        Book::create($request->all()); // INSERT INTO books...

        return redirect()->route('books.index')
                         ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource - READ single (like show_book.php)
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource - EDIT form (like edit_book.php)
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource - EDIT handling (like edit_book.php form action)
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'description' => 'nullable',
            'published_year' => 'required|integer',
        ]);

        $book->update($request->all()); // UPDATE books SET...

        return redirect()->route('books.index')
                         ->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource - DELETE (like delete_book.php)
     */
    public function destroy(Book $book)
    {
        $book->delete(); // DELETE FROM books WHERE id...

        return redirect()->route('books.index')
                         ->with('success', 'Book deleted successfully.');
    }
}