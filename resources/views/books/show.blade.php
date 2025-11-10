@extends('layouts.app')

@section('title', $book->title . ' - Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Book Details and Information</h1>
        </div>
        
        <div>
            <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Books
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Left Column - 2/3 width on large screens -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Book Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Title</label>
                            <p class="text-gray-800 font-medium">{{ $book->title }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Year Published</label>
                            <p class="text-gray-800 font-medium">{{ $book->year_published }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Available Copies</label>
                            <div class="flex items-center">
                                <span class="text-gray-800 font-medium">{{ $book->availableCopies()->count() }}</span>
                                @if($book->availableCopies()->count() > 0)
                                    <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Available</span>
                                @else
                                    <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Unavailable</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                            <span class="bg-purple-100 text-purple-800 department-badge">
                                {{ $book->category->category_name ?? 'Uncategorized' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Authors</label>
                            <div class="flex flex-wrap gap-2">
                                @if($book->authors->isNotEmpty())
                                    @foreach($book->authors as $author)
                                        <a href="{{ route('authors.show', $author->id) }}" 
                                           class="bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors px-3 py-1 rounded-full text-sm font-medium inline-flex items-center group">
                                            {{ $author->appellation ? $author->appellation . ' ' : '' }}
                                            {{ $author->first_name }} 
                                            {{ $author->middle_name ? $author->middle_name . ' ' : '' }}
                                            {{ $author->last_name }}
                                            {{ $author->extension ? ', ' . $author->extension : '' }}
                                            <i class="fas fa-external-link-alt ml-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        </a>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            @if($book->description)
            <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-contract mr-2 text-purple-600"></i>
                    Description
                </h2>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                </div>
            </div>
            @endif

            <!-- Authors Information -->
            <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-users mr-2 text-indigo-600"></i>
                    Authors Information
                </h2>
                
                <div class="space-y-3">
                    @if($book->authors->isNotEmpty())
                        @foreach ($book->authors as $author)
                            <a href="{{ route('authors.show', $author->id) }}" 
                               class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition group cursor-pointer">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3 group-hover:bg-indigo-200 transition">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 group-hover:text-indigo-700 transition">
                                        {{ $author->appellation ? $author->appellation . ' ' : '' }}
                                        {{ $author->first_name }} 
                                        {{ $author->middle_name ? $author->middle_name . ' ' : '' }}
                                        {{ $author->last_name }}
                                        {{ $author->extension ? ', ' . $author->extension : '' }}
                                    </h4>
                                    <p class="text-sm text-gray-600">Author</p>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-users text-4xl mb-2"></i>
                            <p>No authors information available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - 1/3 width on large screens -->
        <div class="space-y-6">
            <!-- Request Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-book mr-2 text-green-600"></i>
                    Request Book
                </h2>

                <div class="flex flex-col items-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="fas fa-book-open text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 text-center">
                        {{ Str::limit($book->title, 50) }}
                    </h3>
                    <p class="text-gray-600 text-center mt-1">{{ $book->category->category_name ?? 'General' }}</p>
                </div>

                <div class="space-y-4">
                    @auth
                        @php
                            $user = Auth::user();
                            $hasOverdue = $user->hasOverdueTransactions();
                            $hasRequested = $book->hasUserRequested($user->id);
                            $canRequest = $book->canBeRequested();
                        @endphp

                        {{-- Case 1: User has overdue transactions --}}
                        @if($hasOverdue)
                            <button disabled
                                    class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center cursor-not-allowed shadow-md">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                You Have Overdue Books
                            </button>
                            <p class="text-sm text-gray-500 text-center">Please return overdue books before requesting new ones.</p>

                        {{-- Case 2: Book can be requested and user has not yet requested --}}
                        @elseif($canRequest && !$hasRequested)
                            <form action="{{ route('transactions.request-book', $book) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center transition shadow-md">
                                    <i class="fas fa-book mr-2"></i>
                                    Request This Book
                                </button>
                            </form>

                        {{-- Case 3: User already has a pending request --}}
                        @elseif($hasRequested)
                            @php
                                $activeTransaction = $book->transactions()
                                    ->where('user_id', $user->id)
                                    ->where('transaction_status', 'requested')
                                    ->latest()
                                    ->first();
                            @endphp

                            @if($activeTransaction)
                                <form action="{{ route('transactions.cancel-request', $activeTransaction->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center transition shadow-md">
                                        <i class="fas fa-clock mr-2"></i>
                                        Cancel Request
                                    </button>
                                </form>
                                <p class="text-sm text-gray-500 text-center">You have a pending request. You may cancel it.</p>
                            @else
                                <button disabled
                                        class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center cursor-not-allowed shadow-md">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    You already borrowed this book
                                </button>
                            @endif

                        {{-- Case 4: No copies available --}}
                        @else
                            <button disabled
                                    class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center cursor-not-allowed shadow-md">
                                <i class="fas fa-times-circle mr-2"></i>
                                No Copies Available
                            </button>
                            <p class="text-sm text-gray-500 text-center">Check back later for availability</p>
                        @endif

                        <div class="text-center">
                            <a href="{{ route('userInterface.borrowedBooks') }}"
                               class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                <i class="fas fa-list mr-1"></i>
                                View My Requests
                            </a>
                        </div>

                    @else
                        <a href="{{ route('login') }}" 
                           class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center transition shadow-md">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login to Request
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Book Statistics -->
            <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                    Book Statistics
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Total Copies</span>
                        <span class="font-medium text-gray-800">{{ $book->copies->count() ?? 1 }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Available Copies</span>
                        <span class="font-medium text-green-600">{{ $book->availableCopies()->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Borrowed Copies</span>
                        <span class="font-medium text-orange-600">{{ ($book->copies->count() ?? 1) - $book->availableCopies()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons for Admin/Librarian -->
    @can('manage-books')
    <div class="bg-white rounded-xl shadow-sm p-6 info-card">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-cog mr-2 text-red-600"></i>
            Management Actions
        </h2>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('books.edit', $book) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-edit mr-2"></i>
                Edit Book
            </a>
            
            <form action="{{ route('books.destroy', $book) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this book? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium flex items-center transition">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Book
                </button>
            </form>

            <a href="{{ route('books.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-list mr-2"></i>
                View All Books
            </a>
        </div>
    </div>
    @endcan
</div>

@push('styles')
<style>
    .info-card {
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    .info-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .department-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
</style>
@endpush
@endsection