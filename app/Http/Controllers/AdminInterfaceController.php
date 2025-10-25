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

        $recentTransactions = Transaction::latest()->take(5)->get();
        $totalBooks = Book::count();
        $totalTheses = Thesis::count();
        $activeUsers = User::where('account_status', 'active')->count();

        $borrowedBooks = Transaction::borrowed()->count();
        $pendingBorrowings = Transaction::pending()->count();
        $overdueBooks = Transaction::overdue()->count();

        return view('adminInterface.index', compact(
            'totalBooks',
            'totalTheses',
            'activeUsers',
            'borrowedBooks',
            'pendingBorrowings',
            'overdueBooks',
            'recentTransactions' 
        ));
    }
}
