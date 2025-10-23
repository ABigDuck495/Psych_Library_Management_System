<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;

//Role Based Access Control Controllers
use App\Http\Controllers\UserInterfaceController;
use App\Http\Controllers\AdminInterfaceController;
use App\Http\Controllers\LibrarianInterfaceController;

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

// Register routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Home route
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect()->route('adminInterface.index'),
            'librarian' => redirect()->route('librarianInterface.index'),
            'user' => redirect()->route('userInterface.index'),
            default => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
})->name('home');

// // Simple index route
// Route::get('/index', function () {
//     return view('index');
// })->name('index');


// Debug routes
Route::get('/whoami', function () {
    if (auth()->check()) {
        return 'Logged in as: ' . auth()->user()->role;
    }
    return 'Not logged in';
});

Route::middleware(['auth', 'role:user'])->get('/test', function () {
    return 'Access granted to user role!';
});

Route::middleware(['auth'])->group(function () {
    // ========== ADMIN & SUPER-ADMIN ROUTES (Most Specific) ==========
    Route::middleware(['role:admin,super-admin'])->group(function () {
        // Full CRUD for users and authors
        Route::resource('users', UserController::class);
        Route::resource('authors', AuthorController::class);
        
        Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

        // Admin-level transaction actions
        Route::get('/requested-books', [TransactionController::class, 'requestedBooks'])->name('transactions.requested-books');
        Route::get('/requested-theses', [TransactionController::class, 'requestedTheses'])->name('transactions.requested-theses');
        Route::get('/transactions/overdue', [TransactionController::class, 'overdueTransactions'])->name('transactions.overdue');
        Route::patch('/transactions/{transaction}/approve', [TransactionController::class, 'approveRequest'])->name('transactions.approve-request');
        Route::patch('/transactions/{transaction}/return', [TransactionController::class, 'returnBook'])->name('transactions.return');
        Route::patch('/transactions/{transaction}/mark-overdue', [TransactionController::class, 'markOverdue'])->name('transactions.mark-overdue');

        // Admin interfaces
        Route::get('/admin/interface', [AdminInterfaceController::class, 'index'])->name('adminInterface.index');
    });

    // ========== LIBRARIAN, ADMIN & SUPER-ADMIN ROUTES ==========
    Route::middleware(['role:librarian,admin,super-admin'])->group(function () {
        // Full CRUD for books and theses (create, edit, delete, etc.)
        Route::resource('books', BookController::class)->except(['index', 'show']);
        Route::resource('theses', ThesisController::class)->except(['index', 'show']);

        // Transactions: librarians manage transactions
        Route::resource('transactions', TransactionController::class);

        // Request actions and renew
        Route::post('/books/{book}/request', [TransactionController::class, 'requestBook'])->name('transactions.request-book');
        Route::post('/theses/{thesis}/request', [TransactionController::class, 'requestThesis'])->name('transactions.request-thesis');
        Route::patch('/transactions/{transaction}/renew', [TransactionController::class, 'renew'])->name('transactions.renew');

        // Librarian interface
        Route::get('/librarian/interface', [LibrarianInterfaceController::class, 'index'])->name('librarianInterface.index');
    });

    // ========== ALL AUTHENTICATED USERS ROUTES (Least Specific) ==========
    Route::middleware(['role:user,librarian,admin,super-admin'])->group(function () {
        // Viewing routes - accessible to any authenticated user
        Route::get('/catalogue', function () {
            return view('catalogue.catalogue');
        })->name('catalogue');

        // Books: users may only view index & show
        Route::get('books', [BookController::class, 'index'])->name('books.index');
        Route::get('books/{book}', [BookController::class, 'show'])->name('books.show');

        // Theses: users may only view index & show
        Route::get('theses', [ThesisController::class, 'index'])->name('theses.index');
        Route::get('theses/{thesis}', [ThesisController::class, 'show'])->name('theses.show');

        // Simple user-facing interface
        Route::get('/user/userInterface', [UserInterfaceController::class, 'index'])->name('userInterface.index');
    });
});

// Login routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('login.post');

// // Role-based dashboards
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/interface', [AdminInterfaceController::class, 'index'])->name('admin.interface');
// });

// Route::middleware(['auth', 'role:librarian'])->group(function () {
//     Route::get('/librarian/interface', [LibrarianInterfaceController::class, 'index'])->name('librarian.interface');
// });

// Route::middleware(['auth', 'role:user'])->group(function () {
//     Route::get('/user/interface', [UserInterfaceController::class, 'index'])->name('user.interface');
// });