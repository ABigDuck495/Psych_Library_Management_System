@extends('layouts.app')

@section('title', 'Add Book - Psych Library')

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
    
    .multi-select {
        height: 150px;
    }
    
    .multi-select option {
        padding: 8px 12px;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .multi-select option:hover {
        background-color: #eff6ff;
    }
</style>
@endpush

@section('content')
<div class="flex-1 p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Add New Book</h1>
            <p class="text-gray-600 mt-2">Add a new book to the library collection</p>
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

    <!-- Add Book Form -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <form action="{{ route('books.store') }}" method="POST" id="bookForm">
            @csrf

            <!-- Form Header -->
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Book Information</h2>
                    <p class="text-gray-600 text-sm">Enter the book details below</p>
                </div>
            </div>

            <!-- Book Details Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Title Field -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title') }}" 
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Enter book title"
                                required
                            >
                        </div>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year Published Field -->
                    <div>
                        <label for="year_published" class="block text-sm font-medium text-gray-700 mb-2">
                            Year Published <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <select  
                                id="year_published" 
                                name="year_published" 
                                value="{{ old('year_published') }}" 
                                min="1900" 
                                max="{{ date('Y') }}"
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Enter publication year"
                                required
                            >
                                @for($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" {{ old('year_published') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        @error('year_published')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Copies Count Field -->
                    <div>
                        <label for="copies_count" class="block text-sm font-medium text-gray-700 mb-2">
                            Number of Copies <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-copy text-gray-400"></i>
                            </div>
                            <input 
                                type="number" 
                                id="copies_count" 
                                name="copies_count" 
                                value="{{ old('copies_count', 1) }}" 
                                min="1" 
                                max="20"
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                required
                            >
                        </div>
                        @error('copies_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Category Field -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <select 
                                id="category_id" 
                                name="category_id" 
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                required
                            >
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Enter book description"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

           <!-- Author Information Section -->
<div class="border-t border-gray-200 pt-6 mb-6">
    <div class="flex items-center mb-6">
        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
            <i class="fas fa-user-edit text-purple-600"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-800">Author Information</h3>
            <p class="text-gray-600 text-sm">Add authors for this book</p>
        </div>
    </div>

    <!-- Dynamic Author Fields -->
    <div id="authorsContainer" class="space-y-4">
        <!-- First Author by default -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 author-row">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Appellation</label>
                <input type="text" name="authors[0][appellation]" placeholder="e.g., Dr., Prof., Mr., Ms."
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                <input type="text" name="authors[0][first_name]" placeholder="First Name" required
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                <input type="text" name="authors[0][middle_name]" placeholder="Middle Name"
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                <input type="text" name="authors[0][last_name]" placeholder="Last Name" required
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Extension</label>
                <input type="text" name="authors[0][extension]" placeholder="e.g., Jr., Sr., III"
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
        </div>
    </div>

    <!-- Add Author Button -->
    <div class="mt-4">
        <button type="button" id="addAuthorBtn"
            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-plus mr-2"></i> Add Author
        </button>
    </div>
</div>


            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                <div>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Books
                    </a>
                </div>
                <div class="flex space-x-3">
                    <button 
                        type="button" 
                        onclick="resetForm()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
                    >
                        <i class="fas fa-redo mr-2"></i>
                        Reset Form
                    </button>
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Save Book
                    </button>
                </div>
            </div>
        </form>
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
                        <li>Ensure all required fields (marked with *) are filled</li>
                        <li>Add at least one author for the book</li>
                        <li>Publication year must be between 1900 and current year</li>
                        <li>Number of copies determines available inventory</li>
                        <li>Use the "Add Author" button if an author is missing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let authorIndex = 1;
    const addAuthorBtn = document.getElementById('addAuthorBtn');
    const authorsContainer = document.getElementById('authorsContainer');

    addAuthorBtn.addEventListener('click', () => {
        const authorRow = document.createElement('div');
        authorRow.classList.add('grid', 'grid-cols-1', 'lg:grid-cols-3', 'gap-4', 'author-row', 'border-b', 'pb-4', 'mt-2');

        authorRow.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Appellation</label>
                <input type="text" name="authors[${authorIndex}][appellation]" placeholder="e.g. Dr., Engr., Prof."
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                <input type="text" name="authors[${authorIndex}][first_name]" placeholder="First Name" required
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                <input type="text" name="authors[${authorIndex}][middle_name]" placeholder="Middle Name"
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                <input type="text" name="authors[${authorIndex}][last_name]" placeholder="Last Name" required
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Extension</label>
                <div class="flex">
                    <input type="text" name="authors[${authorIndex}][extension]" placeholder="e.g. Jr., III"
                        class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
                    <button type="button" class="ml-2 px-3 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-author-btn transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        authorsContainer.appendChild(authorRow);

        authorRow.querySelector('.remove-author-btn').addEventListener('click', () => {
            authorRow.remove();
        });

        authorIndex++;
    });
</script>

<!-- Form Actions -->
<div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
    <div>
        <a href="{{ route('theses.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Cancel
        </a>
    </div>
    <div class="flex space-x-3">
        <button 
            type="button" 
            onclick="resetForm()" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
        >
            <i class="fas fa-redo mr-2"></i>
            Reset Form
        </button>
        <button 
            type="submit" 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
        >
            <i class="fas fa-save mr-2"></i>
            Save Thesis
        </button>
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
                    <li>Ensure all required fields (marked with *) are filled</li>
                    <li>Add at least one author for the thesis</li>
                    <li>Publication year must be between 1900 and current year</li>
                    <li>Use the "Add Author" button if an author is missing</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Form reset function
    function resetForm() {
        document.getElementById('bookForm').reset();
        showTemporaryMessage('Form has been reset', 'blue');
    }

    // Show temporary message
    function showTemporaryMessage(message, type = 'blue') {
        const existingMessage = document.getElementById('temporaryMessage');
        if (existingMessage) existingMessage.remove();

        const messageDiv = document.createElement('div');
        messageDiv.id = 'temporaryMessage';
        const bgColor = type === 'red' ? 'red' : 'blue';
        messageDiv.className = `fixed top-4 right-4 bg-${bgColor}-100 border border-${bgColor}-400 text-${bgColor}-700 px-4 py-3 rounded-lg shadow-lg z-50`;
        messageDiv.innerHTML = `<div class="flex items-center"><i class="fas fa-info-circle mr-2"></i><span>${message}</span></div>`;

        document.body.appendChild(messageDiv);
        setTimeout(() => { messageDiv.remove(); }, 3000);
    }
</script>
@endpush