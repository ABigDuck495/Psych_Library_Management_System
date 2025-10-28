@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $book->title }}</h1>
            <div class="flex items-center space-x-4 text-gray-600">
                <span class="flex items-center">
                    <i class="fas fa-book-open mr-2"></i>
                    Book Details
                </span>
            </div>
        </div>

        <!-- Book Details Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Book Information -->
                <div class="lg:col-span-2">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Book Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Authors</label>
                            <p class="mt-1 text-gray-800">
                                {{ $book->authors->map(function($a) { return trim($a->first_name . ' ' . $a->last_name); })->implode(', ') ?: 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Year Published</label>
                            <p class="mt-1 text-gray-800">{{ $book->year_published }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Category</label>
                            <p class="mt-1 text-gray-800">{{ $book->category->category_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Available Copies</label>
                            <p class="mt-1 text-gray-800 font-semibold">
                                {{ $book->availableCopies()->count() }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Description</label>
                            <p class="mt-1 text-gray-800 leading-relaxed">{{ $book->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Request Section -->
                <div class="flex flex-col justify-center">
                    <div class="text-center">
                        @auth
                            @if($book->canBeRequested() && !$book->hasUserRequested(Auth::id()))
                                <form action="{{ route('transactions.request-book', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                                        <i class="fas fa-hand-paper mr-2"></i>
                                        Request This Book
                                    </button>
                                </form>
                                <p class="text-sm text-gray-600 mt-3">
                                    Click to request this book for borrowing
                                </p>
                            @elseif($book->hasUserRequested(Auth::id()))
                                @php
                                    $activeTransaction = $book->transactions()
                                        ->where('user_id', Auth::id())
                                        ->where('transaction_status', 'requested')
                                        ->latest()
                                        ->first();
                                @endphp

                                @if($activeTransaction)
                                    <form action="{{ route('transactions.cancel-request', $activeTransaction->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                                            <i class="fas fa-clock mr-2"></i>
                                            Cancel Request
                                        </button>
                                    </form>
                                    <p class="text-sm text-gray-600 mt-3">
                                        You have a pending request. You may cancel it.
                                    </p>
                                @else
                                    <button disabled class="bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg opacity-90 cursor-not-allowed">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        No Active Request Found
                                    </button>
                                @endif
                            @else
                                <button disabled class="bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg opacity-90 cursor-not-allowed">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    No Copies Available
                                </button>
                                <p class="text-sm text-gray-600 mt-3">
                                    All copies are currently borrowed
                                </p>
                            @endif

                            <div class="mt-6">
                                <a href="{{ route('userInterface.borrowedBooks') }}" class="text-green-600 hover:text-green-700 font-medium">
                                    <i class="fas fa-list mr-2"></i>
                                    View My Requests
                                </a>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Please log in to request books
                                </p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Display messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Back to Books Link -->
        <div class="mt-8">
            <a href="{{ route('books.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Book Collection
            </a>
        </div>
    </div>
</div>
@endsection