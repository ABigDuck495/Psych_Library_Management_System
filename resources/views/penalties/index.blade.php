@extends('layouts.app')

@section('title', 'Overdue Transactions - Psych Library')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Overdue Transactions</h1>
        <p class="text-gray-600 mt-2">Items that are 7+ days past due date</p>
    </div>
</div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <a href="{{ route('export.penalties', 'all') }}">Export All Penalties</a>
    <a href="{{ route('export.penalties', 'unpaid') }}">Export Unpaid Penalties</a>
    <a href="{{ route('export.penalties', 'recent') }}">Export Recent Penalties</a>
    <a href="{{ route('export.penalties', 'high-value') }}">Export High Value Penalties</a>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Overdue Days</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penalty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($overdueTransactions as $transaction)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $transaction->user->first_name }} {{ $transaction->user->last_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($transaction->book)
                            Book: {{ $transaction->book->title }}
                        @elseif($transaction->thesis)
                            Thesis: {{ $transaction->thesis->title }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($transaction->due_date)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-red-600 font-semibold">
                        {{ $transaction->getOverdueDays() }} days
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($transaction->penalty)
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">
                                ₱{{ $transaction->penalty->amount }}
                            </span>
                        @else
                            <span class="text-gray-500">No penalty</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-900">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection