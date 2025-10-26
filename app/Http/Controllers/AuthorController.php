<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthorController extends Controller
{
    // List authors with optional search
    public function index(Request $request)
    {
        $query = Author::withCount(['books', 'theses']);

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%");
            });
        }

        $authors = $query->paginate(25);

        return view('authors.index', compact('authors'));
    }

    // Show create author form
    public function create()
    {
        return view('authors.create');
    }

    // Store a new author
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
        ]);

        $author = Author::create($request->only('first_name', 'last_name'));

        // Redirect back to a specific page if 'return_to' exists (e.g., from thesis form)
        if ($request->has('return_to') && !empty($request->return_to)) {
            return redirect($request->return_to)->with([
                'success' => 'Author created successfully',
                'new_author_id' => $author->id
            ]);
        }

        return redirect()->route('authors.index')->with('success', 'Author added successfully!');
    }

    // Show edit author form
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    // Update author details
    public function update(Request $request, Author $author)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
        ]);

        $author->update($request->only('first_name', 'last_name'));

        return redirect()->route('authors.index')->with('success', 'Author updated successfully!');
    }

    // Delete an author
    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Author deleted successfully!');
    }

    // Show books by this author
    public function books(Author $author)
    {
        $books = $author->books()->paginate(10);
        return view('authors.books', compact('author', 'books'));
    }

    // Show theses by this author
    public function theses(Author $author)
    {
        $theses = $author->theses()->paginate(10); // ✅ corrected plural
        return view('authors.theses', compact('author', 'theses'));
    }
}
