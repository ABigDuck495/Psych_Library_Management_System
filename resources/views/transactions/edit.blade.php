@extends('layouts.app')

@section('title', 'Edit Transaction')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Transaction #{{ $transaction->id }}</h3>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back to Transactions
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- User Information (Read-only) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">User</label>
                                    <input type="text" class="form-control" value="{{ $transaction->user->full_name }} ({{ $transaction->user->university_id }})" readonly>
                                    <input type="hidden" name="user_id" value="{{ $transaction->user_id }}">
                                </div>
                            </div>

                            <!-- Item Information (Read-only) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item</label>
                                    <input type="text" class="form-control" value="{{ $transaction->item_title }} ({{ $transaction->item_type }})" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Transaction Status -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="transaction_status">Status *</label>
                                    <select name="transaction_status" id="transaction_status" class="form-control @error('transaction_status') is-invalid @enderror" required>
                                        <option value="requested" {{ old('transaction_status', $transaction->transaction_status) == 'requested' ? 'selected' : '' }}>Requested</option>
                                        <option value="approved" {{ old('transaction_status', $transaction->transaction_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="borrowed" {{ old('transaction_status', $transaction->transaction_status) == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                        <option value="returned" {{ old('transaction_status', $transaction->transaction_status) == 'returned' ? 'selected' : '' }}>Returned</option>
                                        <option value="overdue" {{ old('transaction_status', $transaction->transaction_status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                        <option value="cancelled" {{ old('transaction_status', $transaction->transaction_status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('transaction_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Borrow Date -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="borrow_date">Borrow Date *</label>
                                    <input type="date" name="borrow_date" id="borrow_date" 
                                           class="form-control @error('borrow_date') is-invalid @enderror"
                                           value="{{ old('borrow_date', $transaction->borrow_date->format('Y-m-d')) }}" required>
                                    @error('borrow_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="due_date">Due Date *</label>
                                    <input type="date" name="due_date" id="due_date" 
                                           class="form-control @error('due_date') is-invalid @enderror"
                                           value="{{ old('due_date', $transaction->due_date->format('Y-m-d')) }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Return Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="return_date">Return Date</label>
                                    <input type="date" name="return_date" id="return_date" 
                                           class="form-control @error('return_date') is-invalid @enderror"
                                           value="{{ old('return_date', $transaction->return_date ? $transaction->return_date->format('Y-m-d') : '') }}">
                                    @error('return_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Overdue Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Current Status</label>
                                    <div class="form-control" readonly>
                                        @if($transaction->isOverdue())
                                            <span class="badge badge-danger">OVERDUE ({{ $transaction->getOverdueDays() }} days)</span>
                                        @elseif($transaction->transaction_status === 'returned')
                                            <span class="badge badge-success">RETURNED</span>
                                        @elseif($transaction->transaction_status === 'borrowed')
                                            <span class="badge badge-primary">BORROWED</span>
                                            @if($transaction->can_be_renewed)
                                                <span class="badge badge-warning ml-2">Can be renewed</span>
                                            @endif
                                        @else
                                            <span class="badge badge-secondary">{{ strtoupper($transaction->transaction_status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" placeholder="Optional notes about this transaction">{{ old('notes', $transaction->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Transaction Details -->
                        <div class="alert alert-info">
                            <h6>Transaction Details:</h6>
                            <strong>User:</strong> {{ $transaction->user->full_name }} ({{ $transaction->user->university_id }})<br>
                            <strong>Item:</strong> {{ $transaction->item_title }}<br>
                            <strong>Type:</strong> {{ $transaction->item_type }}<br>
                            <strong>Created:</strong> {{ $transaction->created_at->format('M d, Y h:i A') }}<br>
                            <strong>Last Updated:</strong> {{ $transaction->updated_at->format('M d, Y h:i A') }}
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Transaction
                            </button>
                            
                            @if(in_array(auth()->user()->role, ['admin', 'super-admin', 'librarian']))
                                @if($transaction->transaction_status === 'requested')
                                    <a href="{{ route('transactions.approve', $transaction) }}" class="btn btn-success" 
                                       onclick="return confirm('Approve this request?')">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                @endif
                                
                                @if($transaction->transaction_status === 'approved')
                                    <a href="{{ route('transactions.borrow', $transaction) }}" class="btn btn-info" 
                                       onclick="return confirm('Mark as borrowed?')">
                                        <i class="fas fa-book"></i> Mark as Borrowed
                                    </a>
                                @endif
                                
                                @if($transaction->transaction_status === 'borrowed')
                                    <a href="{{ route('transactions.return', $transaction) }}" class="btn btn-success" 
                                       onclick="return confirm('Mark as returned?')">
                                        <i class="fas fa-undo"></i> Mark as Returned
                                    </a>
                                    
                                    <a href="{{ route('transactions.renew', $transaction) }}" class="btn btn-warning" 
                                       onclick="return confirm('Renew for 7 days?')">
                                        <i class="fas fa-redo"></i> Renew
                                    </a>
                                @endif
                                
                                @if($transaction->transaction_status === 'requested')
                                    <a href="{{ route('transactions.cancel', $transaction) }}" class="btn btn-danger" 
                                       onclick="return confirm('Cancel this request?')">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                @endif
                            @endif
                            
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-set return date when status is set to returned
    $('#transaction_status').change(function() {
        if ($(this).val() === 'returned' && !$('#return_date').val()) {
            $('#return_date').val('{{ date("Y-m-d") }}');
        }
    });

    // Set minimum dates
    $('#borrow_date').change(function() {
        var borrowDate = $(this).val();
        $('#due_date').attr('min', borrowDate);
    });

    $('#due_date').change(function() {
        var dueDate = $(this).val();
        $('#return_date').attr('min', dueDate);
    });
});
</script>
@endpush