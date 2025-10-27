@extends('layouts.app')

@section('title', 'Create Transaction')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Transaction</h3>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        <!-- Step 1: User Information -->
                        <div class="form-section">
                            <h5 class="text-primary mb-4">Step 1: User Information</h5>
                            
                            <div class="form-group">
                                <label for="user_id">User *</label>
                                @if(auth()->user()->isAdmin())
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', auth()->id()) == $user->id ? 'selected' : '' }}>
                                                {{ $user->full_name }} ({{ $user->university_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" value="{{ auth()->user()->full_name }} ({{ auth()->user()->university_id }})" readonly>
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                @endif
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="text-right mt-4">
                                <button type="button" class="btn btn-primary next-step" data-next="step2">Next: Select Item Type <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- Step 2: Item Type Selection -->
                        <div class="form-section d-none">
                            <h5 class="text-primary mb-4">Step 2: Select Item Type</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card item-type-card" data-type="book">
                                        <div class="card-body text-center">
                                            <i class="fas fa-book fa-3x text-primary mb-3"></i>
                                            <h5>Book</h5>
                                            <p class="text-muted">Borrow a book from the library</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card item-type-card" data-type="thesis">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                                            <h5>Thesis</h5>
                                            <p class="text-muted">Borrow a thesis document</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="borrowable_type" id="borrowable_type" value="{{ old('borrowable_type') }}">
                            
                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary prev-step" data-prev="step1">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Book Selection -->
                        <div class="form-section d-none" id="book-section">
                            <h5 class="text-primary mb-4">Step 3: Select Book</h5>
                            
                            <div class="form-group">
                                <label for="book_id">Select Book *</label>
                                <select name="borrowable_id" id="book_id" class="form-control @error('borrowable_id') is-invalid @enderror">
                                    <option value="">Select a Book</option>
                                    @foreach($availableBooks as $book)
                                        <option value="{{ $book->id }}" {{ old('borrowable_id') == $book->id ? 'selected' : '' }}>
                                            {{ $book->title }} 
                                            @if($book->authors->count() > 0)
                                                - by {{ $book->authors->pluck('full_name')->implode(', ') }}
                                            @endif
                                            ({{ $book->year_published }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('borrowable_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary prev-step" data-prev="step2">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" class="btn btn-primary next-step" data-next="step4">Next: Transaction Details <i class="fas fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Thesis Selection -->
                        <div class="form-section d-none" id="thesis-section">
                            <h5 class="text-primary mb-4">Step 3: Select Thesis</h5>
                            
                            <div class="form-group">
                                <label for="thesis_id">Select Thesis *</label>
                                <select name="borrowable_id" id="thesis_id" class="form-control @error('borrowable_id') is-invalid @enderror">
                                    <option value="">Select a Thesis</option>
                                    @foreach($availableTheses as $thesis)
                                        <option value="{{ $thesis->id }}" {{ old('borrowable_id') == $thesis->id ? 'selected' : '' }}>
                                            {{ $thesis->title }} 
                                            @if($thesis->authors->count() > 0)
                                                - by {{ $thesis->authors->pluck('full_name')->implode(', ') }}
                                            @endif
                                            ({{ $thesis->year_published }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('borrowable_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary prev-step" data-prev="step2">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" class="btn btn-primary next-step" data-next="step4">Next: Transaction Details <i class="fas fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Transaction Details -->
                        <div class="form-section d-none">
                            <h5 class="text-primary mb-4">Step 4: Transaction Details</h5>
                            
                            <div class="row">
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
                                
                                <div class="col-md-6">
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
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3" placeholder="Optional notes about this transaction">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Summary -->
                            <div class="alert alert-info">
                                <h6>Transaction Summary:</h6>
                                <div id="summary-user"><strong>User:</strong> <span id="summary-user-name"></span></div>
                                <div id="summary-item"><strong>Item:</strong> <span id="summary-item-name"></span></div>
                                <div id="summary-type"><strong>Type:</strong> <span id="summary-type-name"></span></div>
                                <div id="summary-status"><strong>Status:</strong> <span id="summary-status-name"></span></div>
                                <div id="summary-due"><strong>Due Date:</strong> <span id="summary-due-date"></span></div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary prev-step" data-prev="step3">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Create Transaction
                                    </button>
                                </div>
                            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.form-section');
    let currentStep = 0;
    
    // Show first section
    sections[0].classList.remove('d-none');
    
    // Next step buttons
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const nextStep = this.getAttribute('data-next');
            goToStep(nextStep);
        });
    });
    
    // Previous step buttons
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            const prevStep = this.getAttribute('data-prev');
            goToStep(prevStep);
        });
    });
    
    // Item type selection
    document.querySelectorAll('.item-type-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            document.querySelectorAll('.item-type-card').forEach(c => {
                c.classList.remove('border-primary');
            });
            
            // Add active class to selected card
            this.classList.add('border-primary');
            
            const itemType = this.getAttribute('data-type');
            document.getElementById('borrowable_type').value = 
                itemType === 'book' ? 'App\\Models\\BookCopy' : 'App\\Models\\ThesisCopy';
            
            // Show appropriate section
            if (itemType === 'book') {
                document.getElementById('book-section').classList.remove('d-none');
                document.getElementById('thesis-section').classList.add('d-none');
            } else {
                document.getElementById('thesis-section').classList.remove('d-none');
                document.getElementById('book-section').classList.add('d-none');
            }
            
            // Update step tracking
            currentStep = 2;
        });
    });
    
    // Update summary when on step 4
    function updateSummary() {
        // User summary
        const userSelect = document.getElementById('user_id');
        if (userSelect) {
            const selectedUser = userSelect.options[userSelect.selectedIndex];
            document.getElementById('summary-user-name').textContent = selectedUser.text;
        } else {
            document.getElementById('summary-user-name').textContent = '{{ auth()->user()->full_name }}';
        }
        
        // Item summary
        const bookSelect = document.getElementById('book_id');
        const thesisSelect = document.getElementById('thesis_id');
        let selectedItem = '';
        
        if (bookSelect && bookSelect.value) {
            selectedItem = bookSelect.options[bookSelect.selectedIndex].text;
            document.getElementById('summary-type-name').textContent = 'Book';
        } else if (thesisSelect && thesisSelect.value) {
            selectedItem = thesisSelect.options[thesisSelect.selectedIndex].text;
            document.getElementById('summary-type-name').textContent = 'Thesis';
        }
        
        document.getElementById('summary-item-name').textContent = selectedItem || 'Not selected';
        
        // Status and due date
        const statusSelect = document.getElementById('transaction_status');
        if (statusSelect) {
            document.getElementById('summary-status-name').textContent = 
                statusSelect.options[statusSelect.selectedIndex].text;
        }
        
        const dueDate = document.getElementById('due_date');
        if (dueDate) {
            document.getElementById('summary-due-date').textContent = dueDate.value;
        }
    }
    
    function goToStep(step) {
        // Update summary before showing step 4
        if (step === 'step4') {
            updateSummary();
        }
        
        // Hide all sections
        sections.forEach(section => {
            section.classList.add('d-none');
        });
        
        // Show target section based on step
        switch(step) {
            case 'step1':
                sections[0].classList.remove('d-none');
                currentStep = 0;
                break;
            case 'step2':
                sections[1].classList.remove('d-none');
                currentStep = 1;
                break;
            case 'step3':
                // Determine which step 3 to show based on item type
                const itemType = document.getElementById('borrowable_type').value;
                if (itemType === 'App\\Models\\BookCopy') {
                    document.getElementById('book-section').classList.remove('d-none');
                } else {
                    document.getElementById('thesis-section').classList.remove('d-none');
                }
                currentStep = 2;
                break;
            case 'step4':
                sections[3].classList.remove('d-none');
                currentStep = 3;
                break;
        }
    }
    
    // Set minimum due date to today
    const dueDateInput = document.getElementById('due_date');
    if (dueDateInput) {
        const today = new Date().toISOString().split('T')[0];
        dueDateInput.min = today;
    }
});
</script>
@endpush

@push('styles')
<style>
.item-type-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #dee2e6;
}

.item-type-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
}

.item-type-card.border-primary {
    border-color: #007bff !important;
    background-color: #f8f9fa;
}

.form-section {
    padding: 20px 0;
}
</style>
@endpush