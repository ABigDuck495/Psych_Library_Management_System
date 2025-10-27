@extends('layouts.app')

@section('title', 'Borrowing History - Psych Library')

@push('styles')
<style>
    .history-container {
        background-color: #f9fafb;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .history-table th {
        background-color: #1e293b;
        color: #fff;
    }

    .history-table td, .history-table th {
        text-align: center;
        vertical-align: middle;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.6em;
    }

    .sub-title {
        font-weight: bold;
        color: #1e293b;
        margin-bottom: 1rem;
        text-transform: uppercase;
        border-bottom: 3px solid #2563eb;
        display: inline-block;
        padding-bottom: 4px;
    }

    /* Status badges */
    .badge-returned { background-color: #10b981; color: #fff; }
    .badge-overdue { background-color: #ef4444; color: #fff; }
    .badge-borrowed { background-color: #3b82f6; color: #fff; }
    .badge-pending { background-color: #f59e0b; color: #000; }
    .badge-other { background-color: #6b7280; color: #fff; }

    .type-badge-book { background-color: #3b82f6; color: #fff; }
    .type-badge-thesis { background-color: #8b5cf6; color: #fff; }
</style>
@endpush

@section('content')
<div class="p-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Borrowing History</h1>
        <p class="text-gray-600 mt-2">View your complete borrowing and return history</p>
    </div>

    <div class="history-container">
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 shadow-sm border">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-book text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Items</p>
                        <p class="text-xl font-bold text-gray-800">{{ $historyTransactions->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Returned</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $historyTransactions->where('transaction_status', 'returned')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $historyTransactions->where('transaction_status', 'pending')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm border">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Overdue</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $historyTransactions->filter(function($transaction) { return $transaction->isOverdue(); })->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="sub-title">Transaction History</h2>

        @if($historyTransactions->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-history text-5xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No borrowing history found</h3>
                <p class="text-gray-500 mb-6">Your borrowing history will appear here once you start borrowing items.</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-book mr-2"></i>
                        Browse Books
                    </a>
                    <a href="{{ route('theses.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-file-alt mr-2"></i>
                        Browse Theses
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden history-table">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Authors</th>
                            <th class="px-4 py-3">Borrow Date</th>
                            <th class="px-4 py-3">Due Date</th>
                            <th class="px-4 py-3">Return Date</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyTransactions as $index => $transaction)
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
                                        {{ $transaction->copy instanceof \App\Models\BookCopy ? 'type-badge-book' : 'type-badge-thesis' }}">
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
                                <td class="px-4 py-3">
                                    {{ $transaction->borrow_date ? $transaction->borrow_date->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-4 py-3 {{ $transaction->isOverdue() && $transaction->transaction_status !== 'returned' ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $transaction->due_date ? $transaction->due_date->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $transaction->return_date ? $transaction->return_date->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($transaction->transaction_status === 'returned')
                                        <span class="badge badge-returned">Returned</span>
                                    @elseif($transaction->isOverdue())
                                        <span class="badge badge-overdue">Overdue</span>
                                    @elseif($transaction->transaction_status === 'borrowed')
                                        <span class="badge badge-borrowed">Borrowed</span>
                                    @elseif($transaction->transaction_status === 'pending')
                                        <span class="badge badge-pending">Pending</span>
                                    @else
                                        <span class="badge badge-other">{{ ucfirst($transaction->transaction_status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination (if applicable) -->
            @if(method_exists($historyTransactions, 'links'))
                <div class="mt-6">
                    {{ $historyTransactions->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection