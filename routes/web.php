<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('index');
});

Route::resource('authors', AuthorController::class);

Route::get('/catalogue', function () {
    return view('catalouge.catalogue');
})->name('catalogue');

Route::get('/books/add-inventory', [BookController::class, 'addInventory'])->name('books.addInventory');
Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
Route::get('/books/add-inventory', [BookController::class, 'addInventory'])->name('books.addInventory');
Route::post('/books/store', [BookController::class, 'store'])->name('books.store');