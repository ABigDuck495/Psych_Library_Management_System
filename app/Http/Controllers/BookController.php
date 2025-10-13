<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // shows all books (like index.php)
    public function index()
    {
        $books = Book::all(); // SELECT * FROM books
        return view('books.index', compact('books'));
    }

    // creates new book (like create.php)
    public function create()
    {
        return view('books.create');
    }

    // stores new book (like store.php)
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

    //displays a specific book (like show.php)
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    // edits a specific book (like edit.php)
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    // updates a specific book (like update.php)
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

    // deletes a specific book (like delete.php)
    public function destroy(Book $book)
    {
        $book->delete(); // DELETE FROM books WHERE id...

        return redirect()->route('books.index')
                         ->with('success', 'Book deleted successfully.');
    }
}