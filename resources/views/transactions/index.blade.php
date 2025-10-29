@extends('layouts.app')

@section('title', 'Transactions Management - Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Transactions Management</h1>
            <p class="text-gray-600 mt-2">Manage book and thesis borrowing transactions</p>
        </div>
        
        <div class="flex space-x-3">
            <!-- Export Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="export-dropdown-btn bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                    <i class="fas fa-file-export mr-2"></i>
                    Export Transactions
                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div x-show="open" 
                     @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg z-10 border border-gray-200 py-2">
                    <!-- Export Options -->
                    <div class="px-4 py-2">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Export Transactions</h3>
                        <div class="space-y-1">
                            <a href="{{ route('export.transactions', 'all') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                                <i class="fas fa-download mr-2 text-blue-600"></i>
                                All Transactions
                            </a>
                            <a href="{{ route('export.transactions', 'overdue') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-lg transition">
                                <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                                Overdue Transactions
                            </a>
                            <a href="{{ route('export.transactions', 'borrowed') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                                <i class="fas fa-book mr-2 text-blue-600"></i>
                                Borrowed Items
                            </a>
                            <a href="{{ route('export.transactions', 'returned') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition">
                                <i class="fas fa-check mr-2 text-green-600"></i>
                                Returned Items
                            </a>
                            <a href="{{ route('export.transactions', 'requested') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                                <i class="fas fa-clock mr-2 text-orange-600"></i>
                                Requested Items
                            </a>
                            <a href="{{ route('export.transactions', 'active') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition">
                                <i class="fas fa-sync mr-2 text-purple-600"></i>
                                Active Transactions
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- @if(in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian']))
            <a href="{{ route('transactions.create') }}" class="add-transaction-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-medium flex items-center transition shadow-md">
                <i class="fas fa-plus-circle mr-2"></i>
                Create Transaction
            </a>
            @endif -->
        </div>
    </div>

    <!-- Action Buttons for Librarians/Admins -->
    @if(in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian']))
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex space-x-4">
            <!-- <a href="{{ route('transactions.requested-books') }}" class="action-button bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-clock mr-2"></i>
                View Requested Books
            </a> -->
            <a href="{{ route('export.transactions', 'overdue') }}" class="action-button bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Overdue Report
            </a>
        </div>
    </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Statuses</option>
                        <option value="requested" {{ request('status') == 'requested' ? 'selected' : '' }}>Requested</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select name="user_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex-1 transition">
                        Apply Filters
                    </button>
                    <a href="{{ route('transactions.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    @if($transactions->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book/Thesis</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                            <tr class="table-row-hover">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->user->first_name }} {{ $transaction->user->last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="book-title text-sm text-gray-900" title="{{ $transaction->item_title ?? 'N/A' }}">
                                        {{ $transaction->item_title ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $transaction->borrowable_type === 'App\Models\BookCopy' ? 'Book' : 'Thesis' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->transaction_status === 'requested')
                                        <span class="status-badge bg-orange-100 text-orange-800">Requested</span>
                                    @elseif($transaction->transaction_status === 'borrowed')
                                        <span class="status-badge bg-blue-100 text-blue-800">Borrowed</span>
                                    @elseif($transaction->transaction_status === 'returned')
                                        <span class="status-badge bg-green-100 text-green-800">Returned</span>
                                    @elseif($transaction->transaction_status === 'overdue')
                                        <span class="status-badge bg-red-100 text-red-800">Overdue</span>
                                    @else
                                        <span class="status-badge bg-gray-100 text-gray-800">Cancelled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->borrow_date ? $transaction->borrow_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->due_date ? $transaction->due_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->return_date ? $transaction->return_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- <a href="{{ route('transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-900 action-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a> -->
                                        
                                        @if(in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian']))
                                            @if($transaction->transaction_status === 'requested')
                                                <form action="{{ route('transactions.approve-request', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 action-btn" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($transaction->transaction_status === 'borrowed')
                                                <form action="{{ route('transactions.return', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 action-btn" title="Return">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('transactions.mark-overdue', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 action-btn" title="Mark Overdue">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- <a href="{{ route('transactions.edit', $transaction) }}" class="text-yellow-600 hover:text-yellow-900 action-btn" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                             -->
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Delete this transaction?')" class="text-red-600 hover:text-red-900 action-btn" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            @if($transaction->transaction_status === 'borrowed')
                                                <form action="{{ route('transactions.return', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 action-btn" title="Return">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('transactions.renew', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 action-btn" title="Renew">
                                                        <i class="fas fa-sync"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="fas fa-exchange-alt text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-medium text-gray-700 mb-2">No transactions found</h3>
            <p class="text-gray-500 mb-6">Try adjusting your search or filter to find what you're looking for.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .table-row-hover:hover {
        background-color: #f8fafc;
    }
    
    .action-btn {
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    .add-transaction-btn {
        transition: all 0.3s ease;
    }
    
    .add-transaction-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .export-dropdown-btn {
        transition: all 0.3s ease;
    }
    
    .export-dropdown-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .action-button {
        transition: all 0.3s ease;
    }
    
    .action-button:hover {
        transform: translateY(-1px);
    }
    
    .book-title {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endpush
@endsection