<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount(['books', 'theses']);

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $authors = $query->orderByName()->paginate(25);

        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        Author::create($request->only('first_name', 'last_name'));

        return redirect()->route('authors.index')->with('success', 'Author added successfully!');
    }

    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $author->update($request->only('first_name', 'last_name'));

        return redirect()->route('authors.index')->with('success', 'Author updated successfully!');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Author deleted successfully!');
    }
    public function books(Author $author)
    {
        $books = $author->books()->paginate(10);
        return view('authors.books', compact('author', 'books'));
    }
    public function theses(Author $author)
    {
        $theses = $author->thesis()->paginate(10);
        return view('authors.theses', compact('author', 'theses'));
    }
}
