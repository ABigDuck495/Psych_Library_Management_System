@extends('layouts.app')

@section('title', 'Add Author - Psych Library')

@push('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .dashboard-bg {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    }
    
    .form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
@endpush

@section('content')
<div class="flex-1 p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Add New Author</h1>
            <p class="text-gray-600 mt-2">Add a new author to the library system</p>
        </div>
        <!-- Notification and message icons removed -->
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <strong class="font-medium">Please fix the following errors:</strong>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Add Author Form -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="max-w-2xl">
            <form action="{{ route('authors.store') }}" method="POST" id="authorForm">
                @csrf
                <!-- Hidden field for return URL -->
                <input type="hidden" name="return_to" value="{{ request('return_to', url()->previous()) }}">

                <!-- Form Header -->
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                        <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Author Information</h2>
                        <p class="text-gray-600 text-sm">Enter the author's details below</p>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="space-y-6">
                    <!-- First Name Field -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                value="{{ old('first_name') }}" 
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Enter first name"
                                required
                            >
                        </div>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name Field -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                value="{{ old('last_name') }}" 
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Enter last name"
                                required
                            >
                        </div>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                    <div>
                        <a href="{{ route('authors.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Authors
                        </a>
                    </div>
                    <div class="flex space-x-3">
                        <button 
                            type="button" 
                            onclick="resetForm()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
                        >
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </button>
                        <button 
                            type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                        >
                            <i class="fas fa-save mr-2"></i>
                            Save Author
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Tips -->
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-500 text-xl mt-1"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-blue-800">Quick Tips</h3>
                <div class="mt-2 text-blue-700 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Ensure the author's name is spelled correctly</li>
                        <li>Check if the author already exists in the system before adding</li>
                        <li>Use the full legal name for proper attribution</li>
                        <li>This author will be available for both books and theses</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form reset function
    function resetForm() {
        document.getElementById('authorForm').reset();
        
        // Show reset confirmation
        showTemporaryMessage('Form has been reset', 'blue');
    }

    // Show temporary message
    function showTemporaryMessage(message, type = 'blue') {
        // Remove existing temporary message if any
        const existingMessage = document.getElementById('temporaryMessage');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Create new message element
        const messageDiv = document.createElement('div');
        messageDiv.id = 'temporaryMessage';
        messageDiv.className = `fixed top-4 right-4 bg-${type}-100 border border-${type}-400 text-${type}-700 px-4 py-3 rounded-lg shadow-lg z-50`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(messageDiv);

        // Remove after 3 seconds
        setTimeout(() => {
            if (document.getElementById('temporaryMessage')) {
                document.getElementById('temporaryMessage').remove();
            }
        }, 3000);
    }

    // Form validation
    document.getElementById('authorForm').addEventListener('submit', function(e) {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        
        if (!firstName || !lastName) {
            e.preventDefault();
            showTemporaryMessage('Please fill in all required fields', 'red');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds if form doesn't submit (fallback)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    // Auto-focus first input
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('first_name').focus();
    });
</script>
@endpush