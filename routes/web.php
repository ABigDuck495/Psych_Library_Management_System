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

    // Public for authenticated 'user' role: view catalogue, books, theses and request
    Route::middleware(['role:user,librarian,admin,super-admin'])->group(function () {
        // catalogue view
        Route::get('/catalogue.catalogue', function () {
            return view('catalogue.catalogue');
        })->name('catalogue');

        // viewing routes
        Route::get('books', [BookController::class, 'index'])->name('books.index');
        Route::get('books/{book}', [BookController::class, 'show'])->name('books.show');

    Route::resource('theses', ThesisController::class);
    Route::resource('theses', ThesisController::class)->except(['show']);

    // --- user routes ---
   Route::get('/user/userInterface', [UserInterfaceController::class, 'index'])->name('userInterface.index');



        // Request actions (users can request)
        Route::post('/books/{book}/request', [TransactionController::class, 'requestBook'])->name('transactions.request-book');
        Route::post('/theses/{thesis}/request', [ThesisController::class, 'request'])->name('transactions.request-thesis');
    });

    // Librarian: basic CRUD for books and theses and can view users
    Route::middleware(['role:librarian,admin,super-admin'])->group(function () {
        Route::resource('books', BookController::class)->except(['index', 'show']);
        Route::resource('theses', ThesisController::class)->except(['index', 'show']);

        // show users
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });

    // Admin & Super-admin: full access including approve/return and user management
    Route::middleware(['role:admin,super-admin'])->group(function () {
        // users management
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

        // authors and transactions
        Route::resource('authors', AuthorController::class);
        Route::resource('transactions', TransactionController::class);
        // Route::resource('requested', TransactionController::class);

        // admin transaction actions
        Route::get('/requested-books', [TransactionController::class, 'requestedBooks'])->name('transactions.requested-books');
        Route::get('/requested-theses', [TransactionController::class, 'requestedTheses'])->name('transactions.requested-theses');
        Route::patch('/transactions/{transaction}/approve', [TransactionController::class, 'approveRequest'])->name('transactions.approve-request');
        Route::patch('/transactions/{transaction}/return', [TransactionController::class, 'returnBook'])->name('transactions.return');
    });

});