<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Thesis;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

class AdminInterfaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,super-admin'); // only admin & super-admin
    }

    public function index()
    {
        // Fetch recent 5 transactions with related user info
        $recentTransactions = Transaction::with([
    'borrowable' => function ($morphTo) {
        $morphTo->morphWith([
            \App\Models\BookCopy::class => ['book'],
            \App\Models\ThesisCopy::class => ['thesis'],
        ]);
    },
])
->latest()
->take(5)
->get();


        $totalBooks = Book::count();
        $totalTheses = Thesis::count();
        $activeUsers = User::where('account_status', 'active')->count();

        // Stats
        $borrowedBooks = Transaction::borrowed()->count();
        $pendingRequests = Transaction::where('transaction_status', 'requested')->count(); 
        $overdueItems = Transaction::overdue()->count();

        return view('adminInterface.index', compact(
            'totalBooks',
            'totalTheses',
            'activeUsers',
            'borrowedBooks',
            'pendingRequests',
            'overdueItems',
            'recentTransactions'
        ));
    }
}
