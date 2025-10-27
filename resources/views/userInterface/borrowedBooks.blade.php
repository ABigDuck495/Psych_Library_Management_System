@extends('layouts.app')

@section('title', 'My Borrowed Items - Psych Library')

@push('styles')
<style>
    .borrowed-container {
        background-color: #f9fafb;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .borrowed-table th {
        background-color: #1e293b;
        color: #fff;
    }

    .borrowed-table td, .borrowed-table th {
        text-align: center;
        vertical-align: middle;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.6em;
    }

    /* Section headings */
    .sub-title {
        font-weight: bold;
        color: #1e293b;
        margin-bottom: 1rem;
        text-transform: uppercase;
        border-bottom: 3px solid #2563eb;
        display: inline-block;
        padding-bottom: 4px;
    }

    .action-btn {
        margin: 2px;
        padding: 6px 12px;
        font-size: 0.875rem;
    }

    /* Status badges */
    .badge-pending { background-color: #f59e0b; color: #000; }
    .badge-borrowed { background-color: #3b82f6; color: #fff; }
    .badge-overdue { background-color: #ef4444; color: #fff; }
    .badge-returned { background-color: #10b981; color: #fff; }
</style>
@endpush

@section('content')
<div class="p-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">My Borrowed Items</h1>
        <p class="text-gray-600 mt-2">Manage your current borrowings and pending requests</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="borrowed-container">
        {{-- ✅ PENDING REQUESTS --}}
        <div class="mb-8">
            <h2 class="sub-title">Pending Requests</h2>
            @if($pendingTransactions->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-clock text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 text-lg">You have no pending requests at the moment.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden borrowed-table">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Authors</th>
                                <th class="px-4 py-3">Date Requested</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingTransactions as $index => $transaction)
                                @php
                                    $item = $transaction->copy;
                                    $title = 'N/A';
                                    $authors = collect();
                                    
                                    if ($transaction->copy instanceof \App\Models\BookCopy) {
                                        $title = $item->book->title ?? 'N/A';
                                        $authors = $item->book->authors ?? collect();
                                    } elseif ($transaction->copy instanceof \App\Models\ThesisCopy) {
                                        $title = $item->thesis->title ?? 'N/A';
                                        $authors = $item->thesis->authors ?? collect();
                                    }
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $title }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $transaction->copy instanceof \App\Models\BookCopy ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $transaction->copy instanceof \App\Models\BookCopy ? 'Book' : 'Thesis' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($authors->count())
                                            {{ $authors->map(function($author) { 
                                                return trim($author->first_name . ' ' . $author->last_name); 
                                            })->implode(', ') }}
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $transaction->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge badge-pending">{{ ucfirst($transaction->transaction_status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ✅ BORROWED ITEMS --}}
        <div>
            <h2 class="sub-title">Currently Borrowed Items</h2>
            @if($borrowedTransactions->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 text-lg">You currently have no borrowed items.</p>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-book mr-2"></i>
                        Browse Books
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden borrowed-table">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Authors</th>
                                <th class="px-4 py-3">Borrow Date</th>
                                <th class="px-4 py-3">Due Date</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowedTransactions as $index => $transaction)
                                @php
                                    $item = $transaction->copy;
                                    $title = 'N/A';
                                    $authors = collect();
                                    
                                    if ($transaction->copy instanceof \App\Models\BookCopy) {
                                        $title = $item->book->title ?? 'N/A';
                                        $authors = $item->book->authors ?? collect();
                                    } elseif ($transaction->copy instanceof \App\Models\ThesisCopy) {
                                        $title = $item->thesis->title ?? 'N/A';
                                        $authors = $item->thesis->authors ?? collect();
                                    }
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $title }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $transaction->copy instanceof \App\Models\BookCopy ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $transaction->copy instanceof \App\Models\BookCopy ? 'Book' : 'Thesis' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($authors->count())
                                            {{ $authors->map(function($author) { 
                                                return trim($author->first_name . ' ' . $author->last_name); 
                                            })->implode(', ') }}
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $transaction->borrow_date->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 {{ $transaction->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $transaction->due_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($transaction->isOverdue())
                                            <span class="badge badge-overdue">Overdue</span>
                                        @elseif($transaction->transaction_status === 'borrowed')
                                            <span class="badge badge-borrowed">Borrowed</span>
                                        @else
                                            <span class="badge badge-returned">{{ ucfirst($transaction->transaction_status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($transaction->transaction_status === 'borrowed')
                                            <div class="flex flex-col space-y-2">
                                                <form action="{{ route('transactions.return', $transaction) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" 
                                                            class="action-btn bg-green-600 hover:bg-green-700 text-white rounded transition"
                                                            onclick="return confirm('Are you sure you want to return this item?')">
                                                        <i class="fas fa-undo mr-1"></i> Return
                                                    </button>
                                                </form>
                                                <form action="{{ route('transactions.renew', $transaction) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" 
                                                            class="action-btn bg-blue-600 hover:bg-blue-700 text-white rounded transition"
                                                            onclick="return confirm('Are you sure you want to renew this item?')">
                                                        <i class="fas fa-redo mr-1"></i> Renew
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">No actions</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection