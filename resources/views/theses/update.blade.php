@extends('layouts.app')

@section('title', 'Edit Thesis')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit Thesis</h1>
        <p class="text-gray-600 mt-2">Update thesis information in the library system</p>
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

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('theses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Theses List
        </a>
    </div>

    <!-- Edit Thesis Form -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <form action="{{ route('theses.update', $thesis->id) }}" method="POST" id="thesisForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Form Header -->
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Edit Thesis Information</h2>
                    <p class="text-gray-600 text-sm">Update the thesis details below</p>
                </div>
            </div>

            <!-- Thesis ID Display -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-id-card mr-2 text-gray-400"></i>
                    <span class="font-medium">Thesis ID:</span>
                    <span class="ml-2 font-mono bg-white px-2 py-1 rounded border">{{ $thesis->id }}</span>
                </div>
            </div>

            <!-- Thesis Details Section -->
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
                                value="{{ old('title', $thesis->title) }}" 
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition"
                                placeholder="Enter thesis title"
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
                                value="{{ old('year_published', $thesis->year_published) }}" 
                                min="1900" 
                                max="{{ date('Y') }}"
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition"
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
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition"
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
                    <!-- Department Field -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <select 
                                id="department" 
                                name="department" 
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition"
                                required
                            >
                                <option value="">-- Select Department --</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept }}" {{ old('department', $thesis->department) == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Abstract Field -->
                    <div>
                        <label for="abstract" class="block text-sm font-medium text-gray-700 mb-2">
                            Abstract <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="abstract" 
                            name="abstract" 
                            rows="6"
                            class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition"
                            placeholder="Enter thesis abstract"
                            required
                        >{{ old('abstract', $thesis->abstract) }}</textarea>
                        @error('abstract')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PDF Submission Field - NEW FIELD ADDED -->
                    <div>
                        <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-2">
                            Thesis PDF File
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-file-pdf text-gray-400"></i>
                            </div>
                            <input 
                                type="file" 
                                id="pdf_file" 
                                name="pdf_file" 
                                accept=".pdf"
                                class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            >
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <p>Upload a PDF file of the thesis (max: 10MB)</p>
                            @if($thesis->pdf_path)
                                <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-green-700 flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        PDF file already uploaded
                                        @if($thesis->pdf_path)
                                            <a href="{{ Storage::url($thesis->pdf_path) }}" target="_blank" class="ml-2 text-blue-600 hover:text-blue-800 underline">
                                                View Current PDF
                                            </a>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                        @error('pdf_file')
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
                        <p class="text-gray-600 text-sm">Update authors for this thesis</p>
                    </div>
                </div>

                <!-- Dynamic Authors Section -->
<div class="mb-4">
    <label class="block text-gray-700 font-semibold mb-2">Authors</label>

    <div id="authors-container">
        @foreach($thesis->authors as $index => $author)
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const authorsContainer = document.getElementById('authors-container');
        const addAuthorButton = document.getElementById('add-author');

        addAuthorButton.addEventListener('click', function () {
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

        authorsContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-author')) {
                e.target.closest('.author-group').remove();
            }
        });
    });
</script>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                <div>
                    <a href="{{ route('theses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Theses
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
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Update Thesis
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Reset form to original values
    function resetForm() {
        document.getElementById('title').value = "{{ $thesis->title }}";
        document.getElementById('abstract').value = `{{ $thesis->abstract }}`;
        document.getElementById('year_published').value = "{{ $thesis->year_published }}";
        document.getElementById('department').value = "{{ $thesis->department }}";
        document.getElementById('copies_count').value = "{{ $copies_count ?? 1 }}";
        document.getElementById('pdf_file').value = '';
        
        // Reset authors selection
        const authorSelect = document.getElementById('author_ids');
        const originalAuthors = @json($thesis->authors->pluck('id')->toArray());
        
        for (let i = 0; i < authorSelect.options.length; i++) {
            authorSelect.options[i].selected = originalAuthors.includes(parseInt(authorSelect.options[i].value));
        }
        
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
    document.getElementById('thesisForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const abstract = document.getElementById('abstract').value.trim();
        const yearPublished = document.getElementById('year_published').value;
        const department = document.getElementById('department').value;
        const authorSelect = document.getElementById('author_ids');
        const selectedAuthors = Array.from(authorSelect.selectedOptions).length;
        const pdfFile = document.getElementById('pdf_file').files[0];
        
        if (!title || !abstract || !yearPublished || !department || selectedAuthors === 0) {
            e.preventDefault();
            showTemporaryMessage('Please fill in all required fields', 'red');
            return false;
        }

        // PDF file size validation (max 10MB)
        if (pdfFile && pdfFile.size > 10 * 1024 * 1024) {
            e.preventDefault();
            showTemporaryMessage('PDF file must be less than 10MB', 'red');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    // Auto-focus title field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('title').focus();
    });

    // PDF file input styling
    document.getElementById('pdf_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.type !== 'application/pdf') {
                showTemporaryMessage('Please select a PDF file', 'red');
                this.value = '';
            } else if (file.size > 10 * 1024 * 1024) {
                showTemporaryMessage('File must be less than 10MB', 'red');
                this.value = '';
            } else {
                showTemporaryMessage('PDF file selected successfully', 'blue');
            }
        }
    });
</script>
@endsection