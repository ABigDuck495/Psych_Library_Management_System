<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BookAuthorController extends Controller
{
    public function index(){
        return view('book_authors.index');
    }
    public function store(Request $request){
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'author_id' => 'required|exists:authors,id',
        ]);

        return redirect()->route('books.index')->with('success', 'Book Created Successfully!');
    }
    public function update(Request $request, $id){
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'author_id' => 'required|exists:authors,id',
        ]);

        $id->update($request->only('book_id', 'author_id'));

        return redirect()->route('books.index')->with('success', 'Book Updated Successfully!');
    }
    public function destroy($id){
        $id->delete();

        return redirect()->route('books.index')->with('success', 'Success eyyyyy!');
    }
}
