<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ThesisController;
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
    return Auth::check() ? redirect()->route('catalogue') : redirect()->route('login');
})->name('home');

// Simple index route for post-login redirect
Route::get('/index', function () {
    return view('index');
})->name('index');

Route::middleware(['auth'])->group(function () {

    Route::get('/catalogue.catalogue', function () {
        return view('catalogue.catalogue');
    })->name('catalogue');

    Route::resource('authors', AuthorController::class);
    Route::resource('transaction', TransactionController::class);


    Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
    Route::resource('books', BookController::class);

    Route::resource('theses', ThesisController::class);
    Route::resource('theses', ThesisController::class)->except(['show']);

    //Transactions
    Route::post('/books/{book}/request', [TransactionController::class, 'requestBook'])->name('transactions.request-book');
    //Route::get('/my-requests', [TransactionController::class, 'myRequests'])->name('transactions.my-requests');
    Route::get('/requested-books', [TransactionController::class, 'requestedBooks'])->name('transactions.requested-books');
    Route::patch('/transactions/{transaction}/approve', [TransactionController::class, 'approveRequest'])->name('transactions.approve-request');
    Route::patch('/transactions/{transaction}/return', [TransactionController::class, 'returnBook'])->name('transactions.return');


});