@extends('layouts.app')

@section('content')
<style>
    .sidebar {
        background-color: #1e293b;
        color: #cbd5e1;
        padding: 20px;
        border-radius: 10px;
        min-height: 80vh;
    }

    .sidebar a {
        color: inherit;
        text-decoration: none;
    }

    .sidebar a:hover {
        background-color: #2563eb;
        color: #fff;
    }

    .active-nav {
        background-color: #2563eb;
        color: #fff;
    }

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

    .section-title {
        font-weight: bold;
        color: #1e293b;
        margin-bottom: 1rem;
        text-transform: uppercase;
        border-bottom: 3px solid #2563eb;
        display: inline-block;
        padding-bottom: 4px;
    }

    @media (max-width: 768px) {
        .sidebar {
            margin-bottom: 20px;
        }
        .history-table th, .history-table td {
            font-size: 0.85rem;
            padding: 0.4rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <div class="mb-8">
                <h2 class="text-sm uppercase tracking-wider text-blue-200 mb-4">Main Navigation</h2>
                <ul class="space-y-2 list-unstyled">
                    <li>
                        <a href="{{ route('userInterface.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-home mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('userInterface.borrowedBooks') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-book mr-3"></i>
                            My Borrowed Items
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('userInterface.borrowingHistory') }}" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-history mr-3"></i>
                            Borrowing History
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-heart mr-3"></i>
                            Favorites
                        </a>
                    </li>
                </ul>
            </div>

            <div class="mb-8">
                <h2 class="text-sm uppercase tracking-wider text-blue-200 mb-4">Library Resources</h2>
                <ul class="space-y-2 list-unstyled">
                    <li>
                        <a href="#theses" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-file-alt mr-3"></i>
                            Academic Theses
                        </a>
                    </li>
                    <li>
                        <a href="#books" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-book-open mr-3"></i>
                            Book Collection
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-3"></i>
                            Advanced Search
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="history-container">
                <h2 class="section-title">Borrowing History</h2>

                @if($historyTransactions->isEmpty())
                    <p class="text-center text-muted">No borrowing history found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped history-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Authors</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historyTransactions as $index => $transaction)
                                    @php
                                        $item = $transaction->copy;

                                        $title = $transaction->copy instanceof \App\Models\BookCopy
                                            ? $item->book->title ?? 'N/A'
                                            : ($transaction->copy instanceof \App\Models\ThesisCopy
                                                ? $item->thesis->title ?? 'N/A'
                                                : 'N/A');

                                        $authors = $transaction->copy instanceof \App\Models\BookCopy
                                            ? $item->book->authors ?? collect()
                                            : ($transaction->copy instanceof \App\Models\ThesisCopy
                                                ? $item->thesis->authors ?? collect()
                                                : collect());
                                    @endphp

                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $title }}</td>
                                        <td>{{ $transaction->copy instanceof \App\Models\BookCopy ? 'Book' : 'Thesis' }}</td>
                                        <td>
                                            @if($authors->count())
                                                {{ $authors->pluck('name')->join(', ') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $transaction->borrow_date ? $transaction->borrow_date->format('M d, Y') : '—' }}</td>
                                        <td>{{ $transaction->due_date ? $transaction->due_date->format('M d, Y') : '—' }}</td>
                                        <td>{{ $transaction->return_date ? $transaction->return_date->format('M d, Y') : '—' }}</td>
                                        <td>
                                            @if($transaction->transaction_status === 'returned')
                                                <span class="badge bg-success">Returned</span>
                                            @elseif($transaction->isOverdue())
                                                <span class="badge bg-danger">Overdue</span>
                                            @elseif($transaction->transaction_status === 'borrowed')
                                                <span class="badge bg-warning text-dark">Borrowed</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($transaction->transaction_status) }}</span>
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
</div>
@endsection
