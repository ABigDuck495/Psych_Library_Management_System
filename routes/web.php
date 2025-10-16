<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ThesisController;

// Public homepage
Route::get('/', function () {
    return view('welcome');
});

// Authenticated dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// =====================
// ADMIN-ONLY ROUTES
// =====================
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // Password reset requests
    Route::get('/admin/password-requests', [PasswordResetRequestController::class, 'index'])->name('admin.password.requests');
    Route::post('/admin/password-requests/{id}/approve', [PasswordResetRequestController::class, 'approve'])->name('admin.password.approve');

    // User management
    Route::resource('users', UserController::class);

    // Catalogue (admin can manage)
    Route::resource('catalogue', CatalogueController::class);

    // Authors (full CRUD)
    Route::resource('authors', AuthorController::class);

    // Books
    Route::get('/books/add-inventory', [BookController::class, 'addInventory'])->name('books.addInventory');
    Route::post('/books/store', [BookController::class, 'store'])->name('books.store');

    // Theses (admin can manage)
    Route::resource('theses', ThesisController::class);
});

// =====================
// REGULAR AUTHENTICATED USER ROUTES
// =====================
Route::middleware(['auth'])->group(function () {
    Route::post('/password/request-reset', [PasswordResetRequestController::class, 'store'])->name('password.request.reset');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Public access to catalogue
    Route::get('/catalogue', function () {
        return view('catalouge.catalogue');
    })->name('catalogue');
});

require __DIR__.'/auth.php';
