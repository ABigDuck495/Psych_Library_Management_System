@extends('layouts.app')

@section('title', 'Create Transaction')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Transaction</h3>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back to Transactions
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- User Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">User *</label>
                                    <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required>
                                        <option value="">Select User</option>
                                        @if(auth()->user()->isAdmin())
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id', auth()->id()) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->full_name }} ({{ $user->university_id }})
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="{{ auth()->id() }}" selected>
                                                {{ auth()->user()->full_name }} ({{ auth()->user()->university_id }})
                                            </option>
                                        @endif
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Item Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="borrowable_type">Item Type *</label>
                                    <select name="borrowable_type" id="borrowable_type" class="form-control @error('borrowable_type') is-invalid @enderror" required>
                                        <option value="">Select Item Type</option>
                                        <option value="App\Models\BookCopy" {{ old('borrowable_type') == 'App\Models\BookCopy' ? 'selected' : '' }}>Book</option>
                                        <option value="App\Models\ThesisCopy" {{ old('borrowable_type') == 'App\Models\ThesisCopy' ? 'selected' : '' }}>Thesis</option>
                                    </select>
                                    @error('borrowable_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Item Selection (Dynamic based on type) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="borrowable_id">Select Item *</label>
                                    <select name="borrowable_id" id="borrowable_id" class="form-control select2 @error('borrowable_id') is-invalid @enderror" required disabled>
                                        <option value="">Select Item Type First</option>
                                    </select>
                                    @error('borrowable_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Transaction Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_status">Status *</label>
                                    <select name="transaction_status" id="transaction_status" class="form-control @error('transaction_status') is-invalid @enderror" required>
                                        <option value="requested" {{ old('transaction_status') == 'requested' ? 'selected' : '' }}>Requested</option>
                                        <option value="approved" {{ old('transaction_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="borrowed" {{ old('transaction_status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                    </select>
                                    @error('transaction_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Borrow Date -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="borrow_date">Borrow Date *</label>
                                    <input type="date" name="borrow_date" id="borrow_date" 
                                           class="form-control @error('borrow_date') is-invalid @enderror"
                                           value="{{ old('borrow_date', date('Y-m-d')) }}" required>
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
                                           value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Return Date -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="return_date">Return Date</label>
                                    <input type="date" name="return_date" id="return_date" 
                                           class="form-control @error('return_date') is-invalid @enderror"
                                           value="{{ old('return_date') }}">
                                    @error('return_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" placeholder="Optional notes about this transaction">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Item Details Preview -->
                        <div id="item-details" class="alert alert-info" style="display: none;">
                            <h6>Item Details:</h6>
                            <div id="item-info"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Transaction
                            </button>
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
    // Initialize Select2
    $('.select2').select2();

    // Load available items based on type
    $('#borrowable_type').change(function() {
        var type = $(this).val();
        var borrowableSelect = $('#borrowable_id');
        
        if (type) {
            borrowableSelect.prop('disabled', false);
            
            // Clear previous options
            borrowableSelect.empty().append('<option value="">Loading...</option>');
            
            // Fetch available items
            $.ajax({
                url: '{{ route("transactions.get-available-items") }}',
                type: 'GET',
                data: {
                    type: type
                },
                success: function(data) {
                    borrowableSelect.empty().append('<option value="">Select Item</option>');
                    
                    if (data.items && data.items.length > 0) {
                        $.each(data.items, function(index, item) {
                            var text = item.item_title;
                            if (item.item_type === 'Book' && item.book) {
                                text += ' - ' + item.book.title + ' (Copy #' + item.id + ')';
                            } else if (item.item_type === 'Thesis' && item.thesis) {
                                text += ' - ' + item.thesis.title + ' (Copy #' + item.id + ')';
                            }
                            
                            borrowableSelect.append(
                                $('<option>', {
                                    value: item.id,
                                    text: text,
                                    'data-item': JSON.stringify(item)
                                })
                            );
                        });
                    } else {
                        borrowableSelect.append('<option value="">No available items found</option>');
                    }
                },
                error: function() {
                    borrowableSelect.empty().append('<option value="">Error loading items</option>');
                }
            });
        } else {
            borrowableSelect.prop('disabled', true).empty().append('<option value="">Select Item Type First</option>');
            $('#item-details').hide();
        }
    });

    // Show item details when item is selected
    $('#borrowable_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var itemData = selectedOption.data('item');
        
        if (itemData) {
            var details = '';
            if (itemData.item_type === 'Book' && itemData.book) {
                details = '<strong>Book:</strong> ' + itemData.book.title + '<br>' +
                         '<strong>Authors:</strong> ' + (itemData.book.author_names || 'N/A') + '<br>' +
                         '<strong>Year:</strong> ' + (itemData.book.year_published || 'N/A');
            } else if (itemData.item_type === 'Thesis' && itemData.thesis) {
                details = '<strong>Thesis:</strong> ' + itemData.thesis.title + '<br>' +
                         '<strong>Department:</strong> ' + (itemData.thesis.department || 'N/A') + '<br>' +
                         '<strong>Year:</strong> ' + (itemData.thesis.year_published || 'N/A');
            }
            
            $('#item-info').html(details);
            $('#item-details').show();
        } else {
            $('#item-details').hide();
        }
    });

    // Set minimum dates
    var today = new Date().toISOString().split('T')[0];
    $('#borrow_date').attr('min', today);
    $('#due_date').attr('min', today);
    $('#return_date').attr('min', today);

    // Auto-set due date based on borrow date
    $('#borrow_date').change(function() {
        var borrowDate = new Date($(this).val());
        if (!isNaN(borrowDate.getTime())) {
            var dueDate = new Date(borrowDate);
            dueDate.setDate(dueDate.getDate() + 14);
            $('#due_date').val(dueDate.toISOString().split('T')[0]);
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.select2-container--default .select2-selection--single {
    height: 38px;
    padding: 6px 12px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
</style>
@endpush