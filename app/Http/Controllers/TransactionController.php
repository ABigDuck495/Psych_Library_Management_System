<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Thesis;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
 
    public function create(){
        return view('transactions.create');
    }
    public function store(Request $request){

        $validated = $request->validate([
            'user_id' => 'required|integer',
            'copy_id' => 'required|integer',
            'return_date' => 'nullable|date',
            'transaction_status' => 'required|string|in:borrow,return',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
    }
    public function edit($id){
        return view('transactions.edit', compact('id'));
    }
    public function update(Request $request, $id){
        
        $request->validate([
            'user_id' => 'required|integer',
            'copy_id' => 'required|integer',
            'return_date' => 'nullable|date',
            'transaction_status' => 'required|string|in:borrow,return',
        ]);

        $id->update($request->all());

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }
    public function requestBook(Request $request, Book $book)
    {
        $user = Auth::user();
        if (!$user) {
            // show the login view located at resources/views/auth/login.blade.php
            return view('auth.login');
        }

        // if (!$book->canBeRequested()) {
        //     return redirect()->back()->with('error', 'This book is currently not available for request.');
        // }

        if ($user->hasPendingRequestForBook($book->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this book.');
        }

        $availableCopy = $book->getNextAvailableCopy();
        if (!$availableCopy) {
            return redirect()->back()->with('error', 'No available copies found for this book.');
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            // use ->id unless your copy model uses copy_id
            'copy_id' => $availableCopy->id,
            'borrow_date' => null,
            'due_date' => null,
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        return redirect()->back()->with('success', 'Book request submitted successfully. Please wait for approval.');
    }

    public function requestThesis(Request $request, Thesis $thesis)
    {
        $user = Auth::user();
        if (!$user) {
            // show the login view located at resources/views/auth/login.blade.php
            return view('auth.login');
        }

        if (!$thesis->canBeRequested()) {
            return redirect()->back()->with('error', 'This thesis is currently not available for request.');
        }

        // ensure the user helper can handle theses too (rename if needed)
        if ($user->hasPendingRequestForBook($thesis->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this thesis.');
        }

        $availableCopy = $thesis->getNextAvailableCopy();
        if (!$availableCopy) {
            return redirect()->back()->with('error', 'No available copies found for this thesis.');
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $availableCopy->id,
            'borrow_date' => null,
            'due_date' => null,
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        return redirect()->back()->with('success', 'Thesis request submitted successfully. Please wait for approval.');
    }


}
