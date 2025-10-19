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
        // Validate and update transaction logic here
        

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }
    public function requestBook(Request $request, Book $book){
        $user = Auth::user();
        //checks if book can be requested
        if (!$book->canBeRequested()) {
            return redirect()->back()->with('error', 'This book is currently not available for request.');
        }
        //checks if user alr requested le book
        if ($user->hasPendingRequestForBook($book->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this book.');
        }
        //gets an available coffee
        $availableCopy = $book->getNextAvailableCopy();

        if (!$availableCopy) {
            return redirect()->back()->with('error', 'No available copies found for this book.');
        }
        //craetes the transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $availableCopy->copy_id,
            'borrow_date' => null,
            'due_date' => null,
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        return redirect()->back()->with('success', 'Book request submitted successfully. Please wait for approval.');
    }
    public function requestThesis(Request $request, Thesis $thesis){
        $user = Auth::user();
        //checks if book can be requested
        if (!$thesis->canBeRequested()) {
            return redirect()->back()->with('error', 'This thesis is currently not available for request.');
        }
        //checks if user alr requested le book
        if ($user->hasPendingRequestForBook($thesis->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this book.');
        }
        //gets an available coffee
        $availableCopy = $thesis->getNextAvailableCopy();

        if (!$availableCopy) {
            return redirect()->back()->with('error', 'No available copies found for this thesis.');
        }
        //craetes the transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $availableCopy->copy_id,
            'borrow_date' => null,
            'due_date' => null,
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        return redirect()->back()->with('success', 'Thesis request submitted successfully. Please wait for approval.');
    }


}
