<?php

namespace App\Http\Controllers;

use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BookCopyController extends Controller
{
    // creates a book copy (like create_book_copy.php)
    public function create(){
        return view('book_copies.create');
    }
    // stores the book lmaooooo idk bruh
    public function store(Request $request){
        $request->validate([
            'book_id' => 'required|integer',
            'status' => 'required|string|max:255',
        ]);

        // Assuming BookCopy is a model representing a copy of a book
        BookCopy::create($request->all());

        return redirect()->route('book_copies.index')
                         ->with('success', 'Book copy created successfully.');
    }
    // shows le bookcopy
    public function show($id){
        $bookCopy = BookCopy::findOrFail($id);
        return view('book_copies.show', compact('bookCopy'));
    }
    //returns how many available copies there are for a book
    public function availableCopies($bookId){
        $availableCopiesCount = BookCopy::where('book_id', $bookId)
                                        ->where('isAvailable', true)
                                        ->count();
        return $availableCopiesCount;
    }
}
