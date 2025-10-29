<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Thesis;
use App\Models\BookCopy;
use App\Models\ThesisCopy;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserInterfaceController extends Controller
{
    public function index()
{
    // Fetch all books with authors and category
    $books = Book::with(['authors', 'category'])->get();

    // Fetch all theses with authors
    $theses = Thesis::with('authors')->get();

    // Get the authenticated user
    $user = auth()->user();

    // Counts for user dashboard
    $pendingCount = $user->transactions()
        ->whereIn('transaction_status', ['requested', 'pending'])
        ->count();

    $borrowedCount = $user->transactions()
        ->where('transaction_status', 'borrowed')
        ->count();

    $overdueCount = $user->transactions()
     ->where(function ($query) {
         $query->where('transaction_status', 'borrowed')
               ->where('due_date', '<', now());
     })
     ->orWhere('transaction_status', 'overdue') // ✅ count manually overdue too
    ->count();

    return view('userInterface.index', compact(
        'books',
        'theses',
        'pendingCount',
        'borrowedCount',
        'overdueCount'
    ));
}


    public function dashboardCounts()
    {
        $userId = Auth::id();

        // Count of pending requests
        $pendingCount = Transaction::where('user_id', $userId)
            ->where('transaction_status', 'pending')
            ->count();

        // Count of currently borrowed items
        $borrowedCount = Transaction::where('user_id', $userId)
            ->where('transaction_status', 'borrowed')
            ->count();

        // Count of overdue items
        $overdueCount = Transaction::where('user_id', $userId)
            ->where('transaction_status', 'borrowed')
            ->where('due_date', '<', now())
            ->count();

        return response()->json([
            'pending' => $pendingCount,
            'borrowed' => $borrowedCount,
            'overdue' => $overdueCount,
        ]);
    }

    public function myBorrowedBooks()
{
    $user = auth()->user();

    $pendingTransactions = Transaction::with(['borrowable' => function ($morphTo) {
        $morphTo->morphWith([
            BookCopy::class => ['book'],
            ThesisCopy::class => ['thesis'],
        ]);
    }])
    ->where('user_id', $user->id)
    ->whereIn('transaction_status', ['requested', 'pending'])
    ->latest()
    ->get();

    $borrowedTransactions = Transaction::with(['borrowable' => function ($morphTo) {
        $morphTo->morphWith([
            BookCopy::class => ['book'],
            ThesisCopy::class => ['thesis'],
        ]);
    }])
    ->where('user_id', $user->id)
    ->whereIn('transaction_status', ['borrowed', 'overdue'])
    ->latest()
    ->get();

    return view('userInterface.borrowedBooks', compact('pendingTransactions', 'borrowedTransactions'));

}

public function borrowingHistory()
{
    $user = auth()->user();

    $historyTransactions = Transaction::with(['borrowable' => function ($morphTo) {
        $morphTo->morphWith([
            BookCopy::class => ['book.authors'], // Load book with authors
            ThesisCopy::class => ['thesis.authors'], // Load thesis with authors
        ]);
    }])
    ->where('user_id', $user->id)
    ->whereIn('transaction_status', ['borrowed', 'overdue', 'returned'])
    ->orderByDesc('borrow_date')
    ->get();

    return view('userInterface.borrowingHistory', compact('historyTransactions'));
}

}
