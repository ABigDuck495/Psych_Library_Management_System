<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ThesisDeptController;
use App\Http\Controllers\TransactionController;



Route::get('/', function () {
    return view('index');
});

Route::resource('users', UserController::class);
Route::resource('categories', CategoryController::class);
Route::resource('books', BookController::class);
Route::resource('theses', ThesisController::class);
Route::resource('authors', AuthorController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('penalties', PenaltyController::class);
Route::resource('bookcopies', BookCopyController::class);
Route::resource('thesisdepts', ThesisDeptController::class);

