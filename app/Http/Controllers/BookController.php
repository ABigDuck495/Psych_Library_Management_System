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

    public function addInventory()
    {
        $categories = Category::all();
        //$departments = ThesisDept::all(); // Fetch all departments from thesis_dept table
        return view('books.addInventory', compact('categories'));
    }

    public function store(Request $request)
    {
        if ($request->type === 'book') {
            // ✅ Create author
            $author = Author::firstOrCreate([
                'firstname' => $request->author_firstname,
                'lastname' => $request->author_lastname,
            ]);

            // ✅ Create book
            $book = Book::create([
                'title' => $request->title,
                'description' => $request->description,
                'year_published' => $request->year_published,
                'category_id' => $request->category_id,
            ]);

            // ✅ Link author to book
            BookAuthor::create([
                'book_id' => $book->id,
                'author_id' => $author->id,
            ]);

            // ✅ Create book copy
            BookCopy::create([
                'book_id' => $book->id,
                'num_copies' => $request->num_copies,
            ]);

            return redirect()->route('catalogue')->with('success', 'Book added successfully!');

        } elseif ($request->type === 'thesis') {
            // ✅ Create author
            $author = Author::firstOrCreate([
                'first_name' => $request->author_firstname,
                'last_name' => $request->author_lastname,
            ]);

            // ✅ Create thesis
            $thesis = Thesis::create([
                'title' => $request->title,
                'abstract' => $request->abstract,
                'year_published' => $request->year_published,
                'advisor' => $request->advisor,
                'category_name' => $request->category_name,
            ]);

            // ✅ Link author to thesis
            ThesisAuthor::create([
                'thesis_id' => $thesis->id,
                'author_id' => $author->id,
            ]);

            return redirect()->route('catalogue')->with('success', 'Academic paper added successfully!');
        }
    }
    
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