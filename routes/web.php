<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\CatalogueController;

Route::get('/', function () {
    return view('index');
});

Route::resource('authors', AuthorController::class);

Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/inventory/select-type', [CatalogueController::class, 'selectType'])->name('inventory.selectType');


Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
Route::resource('books', BookController::class);

Route::resource('theses', ThesisController::class);
Route::resource('theses', ThesisController::class)->except(['show']);