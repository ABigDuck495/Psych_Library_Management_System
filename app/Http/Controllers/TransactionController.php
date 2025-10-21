<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Thesis;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Only admin, super-admin, and librarian can view all transactions
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = Transaction::with(['user', 'copy.book']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('transaction_status', $request->status);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $transactions = $query->latest()->paginate(50);
        $users = User::where('role', 'user')->get();
        

        return view('transactions.index', compact('transactions', 'users'));
    }
    // Admin/Librarian: View requested books for approval
    public function requestedBooks()
    {
        // Only admin, super-admin, and librarian can view requested books
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $requests = Transaction::requested()
            ->with(['user', 'copy.book'])
            ->latest()
            ->paginate(20);

        return view('transactions.requested-books', compact('requests'));
    }

    // List requested theses specifically (if you want a separate view)
    public function requestedTheses()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $requests = Transaction::requested()
            ->where('copy_type', 'like', '%ThesisCopy%')
            ->with(['user', 'copy'])
            ->latest()
            ->paginate(20);

        return view('transactions.requested-theses', compact('requests'));
    }

    public function overdueTransactions()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $transactions = Transaction::overdue()->with(['user', 'copy'])->latest()->paginate(30);
        return view('transactions.overdue', compact('transactions'));
    }

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

        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->only(['user_id','copy_id','return_date','transaction_status','due_date','borrow_date','copy_type']));

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }
    public function requestBook(Request $request, Book $book)
    {
        $user = Auth::user();
        if (!$user) {
            // show the login view located at resources/views/auth/login.blade.php
            return view('auth.login');
        }

        if ($user->hasPendingRequestForBook($book->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this book.');
        }

        // atomic reserve: find one available copy and mark as unavailable inside a DB transaction
        $reservedCopy = DB::transaction(function () use ($book) {
            $copy = $book->copies()->where('is_available', 1)->lockForUpdate()->first();
            if (!$copy) return null;
            $copy->is_available = false;
            $copy->save();
            return $copy;
        });

        if (!$reservedCopy) {
            return redirect()->back()->with('error', 'No available copies found for this book.');
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $reservedCopy->id,
            'copy_type' => get_class($reservedCopy),
            'borrow_date' => now(),
            'due_date' => now()->addDays(14),
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

        if ($user->hasPendingRequestForBook($thesis->id)) {
            return redirect()->back()->with('error', 'You already have a pending request for this thesis.');
        }

        $reservedCopy = DB::transaction(function () use ($thesis) {
            $copy = $thesis->copies()->where('is_available', 1)->lockForUpdate()->first();
            if (!$copy) return null;
            $copy->is_available = false;
            $copy->save();
            return $copy;
        });

        if (!$reservedCopy) {
            return redirect()->back()->with('error', 'No available copies found for this thesis.');
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'copy_id' => $reservedCopy->id,
            'copy_type' => get_class($reservedCopy),
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'return_date' => null,
            'transaction_status' => 'requested',
        ]);

        return redirect()->back()->with('success', 'Thesis request submitted successfully. Please wait for approval.');
    }

    public function approveRequest(Request $request, Transaction $transaction)
    {
        // simple permission check could be added here
        if ($transaction->transaction_status !== 'requested') {
            return redirect()->back()->with('error', 'Only requested transactions can be approved.');
        }

        $transaction->transaction_status = 'borrowed';
        $transaction->borrow_date = $transaction->borrow_date ?? now();
        $transaction->due_date = $transaction->due_date ?? now()->addDays(14);
        $transaction->save();

    // optionally notify user or perform further actions

        return redirect()->back()->with('success', 'Transaction approved.');
    }

    public function returnBook(Request $request, Transaction $transaction)
    {
        if ($transaction->transaction_status === 'returned') {
            return redirect()->back()->with('error', 'Transaction already returned.');
        }

        DB::transaction(function () use ($transaction) {
            $transaction->return_date = now();
            $transaction->transaction_status = 'returned';
            $transaction->save();

            // mark copy available again
            $copy = $transaction->copy;
            if ($copy) {
                $copy->is_available = true;
                $copy->save();
            }
        });

        return redirect()->back()->with('success', 'Item returned successfully.');
    }

    // Renew a borrowed transaction (extend due date)
    public function renew(Request $request, Transaction $transaction)
    {
        $user = auth()->user();

        // allow user to renew their own borrowed transactions, or staff to renew any
        if (!$user || (!in_array($user->role, ['admin','super-admin','librarian']) && $transaction->user_id !== $user->id)) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->transaction_status !== 'borrowed') {
            return redirect()->back()->with('error', 'Only borrowed transactions can be renewed.');
        }

        $transaction->due_date = $transaction->due_date->addDays(7);
        $transaction->save();

        return redirect()->back()->with('success', 'Transaction renewed for 7 days.');
    }

    // Mark a transaction as overdue (admin action)
    public function markOverdue(Request $request, Transaction $transaction)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian'])) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->transaction_status = 'overdue';
        $transaction->save();

        return redirect()->back()->with('success', 'Transaction marked as overdue.');
    }


}
