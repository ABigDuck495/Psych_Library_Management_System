<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\UserInterfaceController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;


//================================gamitin if mag checheck ng roles sa routes====================================================//
Route::get('/whoami', function () {
    if (auth()->check()) {
        return 'Logged in as: ' . auth()->user()->role;
    }
    return 'Not logged in';
});
//==http://127.0.0.1:8000/whoami===gamitin if mag checheck ng roles sa routes====================================================//

//=================for testing=============================================//
Route::middleware(['auth', 'role:user'])->get('/test', function () {
    return 'Access granted to user role!';
});
//=================for testing=============================================//

// Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
// Route::get('/inventory/select-type', [CatalogueController::class, 'selectType'])->name('inventory.selectType');



//login shit
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');


//register

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Home route: redirect guests to login immediately, authenticated users to dashboard
Route::get('/', function () {
    return Auth::check() ? redirect()->route('index') : redirect()->route('login');
})->name('home');

// Simple index route for post-login redirect
Route::get('/index', function () {
    return view('index');
})->name('index');

Route::middleware(['auth'])->group(function () {

    // Viewing routes - accessible to any authenticated user
    Route::middleware(['role:user,librarian,admin,super-admin'])->group(function () {
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

    // Librarian: basic CRUD for books, theses and transactions (create/update/delete)
    Route::middleware(['role:librarian,admin,super-admin'])->group(function () {
        // Provide create/store/edit/update/destroy for books/theses
        Route::resource('books', BookController::class)->except(['index', 'show']);
        Route::resource('theses', ThesisController::class)->except(['index', 'show']);

        // Transactions: librarians manage transactions (index/show/create/store/edit/update/destroy)
        Route::resource('transactions', TransactionController::class);

        // Librarians may also create request actions and renew
        Route::post('/books/{book}/request', [TransactionController::class, 'requestBook'])->name('transactions.request-book');
        Route::post('/theses/{thesis}/request', [TransactionController::class, 'requestThesis'])->name('transactions.request-thesis');
        Route::patch('/transactions/{transaction}/renew', [TransactionController::class, 'renew'])->name('transactions.renew');
    });

    // Admin & Super-admin: full access including user and author management and admin-only transaction actions
    Route::middleware(['role:admin,super-admin'])->group(function () {
        // Full CRUD for users and authors
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

        Route::resource('authors', AuthorController::class);

        // Admin-level transaction actions
        Route::get('/requested-books', [TransactionController::class, 'requestedBooks'])->name('transactions.requested-books');
        Route::get('/requested-theses', [TransactionController::class, 'requestedTheses'])->name('transactions.requested-theses');
        Route::get('/transactions/overdue', [TransactionController::class, 'overdueTransactions'])->name('transactions.overdue');
        Route::patch('/transactions/{transaction}/approve', [TransactionController::class, 'approveRequest'])->name('transactions.approve-request');
        Route::patch('/transactions/{transaction}/return', [TransactionController::class, 'returnBook'])->name('transactions.return');
        Route::patch('/transactions/{transaction}/mark-overdue', [TransactionController::class, 'markOverdue'])->name('transactions.mark-overdue');
    });

    

});