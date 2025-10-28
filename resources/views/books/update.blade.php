@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
<div class="p-8">
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

    <!-- Edit Book Form -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <form action="{{ route('books.update', $book->id) }}" method="POST" id="bookForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Form Header -->
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Edit Book Information</h2>
                    <p class="text-gray-600 text-sm">Update the book details below</p>
                </div>
            </div>

            <!-- Book ID Display -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-id-card mr-2 text-gray-400"></i>
                    <span class="font-medium">Book ID:</span>
                    <span class="ml-2 font-mono bg-white px-2 py-1 rounded border">{{ $book->id }}</span>
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
                                value="{{ old('title', $book->title) }}" 
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
                            <input 
                                type="number" 
                                id="year_published" 
                                name="year_published" 
                                value="{{ old('year_published', $book->year_published) }}" 
                                min="1900" 
                                max="{{ date('Y') }}"
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Enter publication year"
                                required
                            >
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
                                value="{{ old('copies_count', $copies_count ?? 1) }}" 
                                min="1" 
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
                                    <option value="{{ $id }}" {{ old('category_id', $book->category_id) == $id ? 'selected' : '' }}>
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
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="6"
                            class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Enter book description"
                            required
                        >{{ old('description', $book->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>


            <!-- Author Information Section -->
            <div class="border-t border-gray-200 pt-6 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-user-edit text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Author Information</h3>
                        <p class="text-gray-600 text-sm">Update authors for this book</p>
                    </div>
                </div>

                <!-- Dynamic Authors Section -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Authors</label>

                    <div id="authors-container">
                        @foreach($book->authors as $index => $author)
                            <div class="author-group flex space-x-2 mb-2">
                                <input type="text" name="authors[{{ $index }}][appellation]" value="{{ $author->appellation }}" placeholder="Appellation" class="w-1/6 border-gray-300 rounded-lg p-2">
                                <input type="text" name="authors[{{ $index }}][first_name]" value="{{ $author->first_name }}" placeholder="First Name" class="w-1/4 border-gray-300 rounded-lg p-2" required>
                                <input type="text" name="authors[{{ $index }}][middle_name]" value="{{ $author->middle_name }}" placeholder="Middle Name" class="w-1/4 border-gray-300 rounded-lg p-2">
                                <input type="text" name="authors[{{ $index }}][last_name]" value="{{ $author->last_name }}" placeholder="Last Name" class="w-1/4 border-gray-300 rounded-lg p-2" required>
                                <input type="text" name="authors[{{ $index }}][extension]" value="{{ $author->extension }}" placeholder="Extension" class="w-1/6 border-gray-300 rounded-lg p-2">
                                <button type="button" class="remove-author bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">Remove</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-author" class="mt-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        + Add Author
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
                        Update Book
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const authorsContainer = document.getElementById('authors-container');
        const addAuthorButton = document.getElementById('add-author');

        addAuthorButton.addEventListener('click', function() {
            const index = authorsContainer.children.length;
            const authorGroup = document.createElement('div');
            authorGroup.classList.add('author-group', 'flex', 'space-x-2', 'mb-2');
            authorGroup.innerHTML = `
                <input type="text" name="authors[${index}][appellation]" placeholder="Appellation" class="w-1/6 border-gray-300 rounded-lg p-2">
                <input type="text" name="authors[${index}][first_name]" placeholder="First Name" class="w-1/4 border-gray-300 rounded-lg p-2" required>
                <input type="text" name="authors[${index}][middle_name]" placeholder="Middle Name" class="w-1/4 border-gray-300 rounded-lg p-2">
                <input type="text" name="authors[${index}][last_name]" placeholder="Last Name" class="w-1/4 border-gray-300 rounded-lg p-2" required>
                <input type="text" name="authors[${index}][extension]" placeholder="Extension" class="w-1/6 border-gray-300 rounded-lg p-2">
                <button type="button" class="remove-author bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">Remove</button>
            `;
            authorsContainer.appendChild(authorGroup);
        });

        authorsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-author')) {
                e.target.closest('.author-group').remove();
            }
        });
    });

    // Reset form to original values
    function resetForm() {
        document.getElementById('title').value = "{{ $book->title }}";
        document.getElementById('year_published').value = "{{ $book->year_published }}";
        document.getElementById('category_id').value = "{{ $book->category_id }}";
        document.getElementById('copies_count').value = "{{ $copies_count ?? 1 }}";
        document.getElementById('description').value = `{{ $book->description }}`;
        showTemporaryMessage('Form reset to original values', 'blue');
    }

    // Show temporary message
    function showTemporaryMessage(message, type = 'blue') {
        const existingMessage = document.getElementById('temporaryMessage');
        if (existingMessage) {
            existingMessage.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.id = 'temporaryMessage';
        const bgColor = type === 'red' ? 'red' : 'blue';
        messageDiv.className = `fixed top-4 right-4 bg-${bgColor}-100 border border-${bgColor}-400 text-${bgColor}-700 px-4 py-3 rounded-lg shadow-lg z-50`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(messageDiv);

        setTimeout(() => {
            if (document.getElementById('temporaryMessage')) {
                document.getElementById('temporaryMessage').remove();
            }
        }, 3000);
    }

    // Form validation
    document.getElementById('bookForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const yearPublished = document.getElementById('year_published').value;
        const category = document.getElementById('category_id').value;
        
        if (!title || !description || !yearPublished || !category) {
            e.preventDefault();
            showTemporaryMessage('Please fill in all required fields', 'red');
            return false;
        }

        // Validate author fields
        const authorGroups = document.querySelectorAll('.author-group');
        let hasAuthorErrors = false;
        
        authorGroups.forEach(group => {
            const firstName = group.querySelector('input[name*="[first_name]"]').value.trim();
            const lastName = group.querySelector('input[name*="[last_name]"]').value.trim();
            
            if (!firstName || !lastName) {
                hasAuthorErrors = true;
                group.style.border = '2px solid red';
            } else {
                group.style.border = '';
            }
        });
        
        if (hasAuthorErrors) {
            e.preventDefault();
            showTemporaryMessage('Please fill in all required author fields', 'red');
            return false;
        }
        
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
        submitBtn.disabled = true;
    });

    // Auto-focus title field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('title').focus();
    });

</script>
@endsection