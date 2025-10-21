<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Thesis;

class UserInterfaceController extends Controller
{
    public function index()
    {
        $books = Book::with(['authors', 'category'])->get();
        $theses = Thesis::with('authors')->get();

        return view('userInterface.index', compact('books', 'theses'));
    }
}
