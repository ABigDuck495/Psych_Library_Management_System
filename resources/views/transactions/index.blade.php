<!DOCTYPE html>
<html>
<head>
    <title>All Transactions</title>
</head>
<body>
    <h1>All Transactions</h1>

    @if(in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian']))
        <div>
            <a href="{{ route('transactions.create') }}">
                <button>Create New Transaction</button>
            </a>
            <a href="{{ route('transactions.requested-books') }}">
                <button>View Requested Books</button>
            </a>
            <a href="{{ route('transactions.overdue') }}">
                <button>Overdue Report</button>
            </a>
        </div>
    @endif

    <!-- Filter Form -->
    <form method="GET" action="{{ route('transactions.index') }}" style="margin: 20px 0; padding: 10px; border: 1px solid #ccc;">
        <div>
            <select name="status">
                <option value="">All Statuses</option>
                <option value="requested" {{ request('status') == 'requested' ? 'selected' : '' }}>Requested</option>
                <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>

            <select name="user_id">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->first_name }} {{ $user->last_name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date">
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date">

            <button type="submit">Filter</button>
            <a href="{{ route('transactions.index') }}">Clear</a>
        </div>
    </form>

    @if(session('success'))
        <div style="background: lightgreen; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: lightcoral; padding: 10px; margin: 10px 0;">
            {{ session('error') }}
        </div>
    @endif

    @if($transactions->count() > 0)
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Book</th>
                    <th>Status</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->user->first_name }} {{ $transaction->user->last_name }}</td>
                    <td>
                        @php
                            $copy = $transaction->copy;
                            $title = 'N/A';
                            if ($copy) {
                                if (isset($copy->book) && $copy->book) {
                                    $title = $copy->book->title;
                                } elseif (isset($copy->thesis) && $copy->thesis) {
                                    $title = $copy->thesis->title;
                                }
                            }
                        @endphp
                        {{ $title }}
                    </td>
                    <td>
                        <span style="
                            padding: 3px 8px;
                            border-radius: 3px;
                            font-size: 12px;
                            background: 
                                @if($transaction->transaction_status === 'requested') orange
                                @elseif($transaction->transaction_status === 'borrowed') blue
                                @elseif($transaction->transaction_status === 'returned') green
                                @elseif($transaction->transaction_status === 'overdue') red
                                @else gray @endif;
                            color: white;
                        ">
                            {{ ucfirst($transaction->transaction_status) }}
                        </span>
                    </td>
                    <td>{{ $transaction->borrow_date ? $transaction->borrow_date->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $transaction->due_date ? $transaction->due_date->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $transaction->return_date ? $transaction->return_date->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $transaction) }}">View</a>
                        
                        @if(in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian']))
                            @if($transaction->transaction_status === 'requested')
                                <form action="{{ route('transactions.approve-request', $transaction) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">Approve</button>
                                </form>
                            @endif

                            @if($transaction->transaction_status === 'borrowed')
                                <form action="{{ route('transactions.return', $transaction) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">Return</button>
                                </form>
                                
                                <form action="{{ route('transactions.mark-overdue', $transaction) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">Mark Overdue</button>
                                </form>
                            @endif

                            <a href="{{ route('transactions.edit', $transaction) }}">Edit</a>
                            
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this transaction?')">Delete</button>
                            </form>
                        @else
                            @if($transaction->transaction_status === 'borrowed')
                                <form action="{{ route('transactions.return', $transaction) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">Return</button>
                                </form>
                                
                                <form action="{{ route('transactions.renew', $transaction) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">Renew</button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $transactions->links() }}
        </div>
    @else
        <p>No transactions found.</p>
    @endif
</body>
</html>