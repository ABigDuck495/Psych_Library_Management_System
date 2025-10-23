<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book - Psych Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
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
        
        .sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%);
        }
        
        .active-nav {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid white;
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
</head>
<body class="dashboard-bg min-h-screen">
    <div class="flex">
        <!-- Sidebar Navigation -->
        <div class="sidebar w-64 min-h-screen p-6 text-white">
            <!-- Logo and App Name -->
            <div class="flex items-center mb-10">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center mr-3">
                    <i class="fas fa-book text-blue-800 text-lg"></i>
                </div>
                <h1 class="text-xl font-bold">Psych Library</h1>
            </div>
            
            <!-- User Profile Section -->
            <div class="bg-white/10 rounded-xl p-4 mb-8">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center mr-3">
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                        <p class="text-blue-200 text-sm capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-blue-200">University ID:</span>
                        <span>{{ Auth::user()->university_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-200">Status:</span>
                        <span class="text-green-300">{{ Auth::user()->account_status }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Menu -->
            <div class="mb-8">
                <h2 class="text-sm uppercase tracking-wider text-blue-200 mb-4">Main Navigation</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-home mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Management Sections -->
            <div class="mb-8">
                <h2 class="text-sm uppercase tracking-wider text-blue-200 mb-4">Management</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-users mr-3"></i>
                            User Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-book-open mr-3"></i>
                            Book Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('theses.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-file-alt mr-3"></i>
                            Thesis Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('authors.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-user-edit mr-3"></i>
                            Author Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-exchange-alt mr-3"></i>
                            Transactions
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Logout Button -->
            <div class="mt-auto pt-6 border-t border-blue-400">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-3 rounded-lg hover:bg-blue-700 transition text-left">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Add New Book</h1>
                    <p class="text-gray-600 mt-2">Add a new book to the library collection</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative p-2 text-gray-600 hover:text-blue-600 transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button>
                    
                    <!-- Messages -->
                    <button class="relative p-2 text-gray-600 hover:text-blue-600 transition">
                        <i class="fas fa-envelope text-xl"></i>
                        <span class="absolute top-0 right-0 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">2</span>
                    </button>
                </div>
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
                                    <input 
                                        type="number" 
                                        id="year_published" 
                                        name="year_published" 
                                        value="{{ old('year_published') }}" 
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
                                <p class="text-gray-600 text-sm">Select authors for this book</p>
                            </div>
                        </div>

                        <!-- Authors Selection -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="author_ids" class="block text-sm font-medium text-gray-700 mb-2">
                                    Authors <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="author_ids" 
                                    name="author_ids[]" 
                                    multiple 
                                    class="form-input multi-select block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                    required
                                >
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}"
                                            {{ in_array($author->id, old('author_ids', [])) ? 'selected' : '' }}>
                                            {{ $author->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2 text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Hold Ctrl (or Cmd on Mac) to select multiple authors
                                </div>
                                @error('author_ids')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Add New Author Button -->
                            <div class="flex items-end">
                                <a href="{{ route('authors.create', ['return_to' => url()->current()]) }}" 
                                   class="inline-flex items-center px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition w-full">
                                    <i class="fas fa-user-plus mr-2 text-green-600"></i>
                                    <div class="text-left">
                                        <div class="font-medium">Add New Author</div>
                                        <div class="text-xs text-gray-500">Can't find an author? Add a new one</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Authors Preview -->
                    <div id="selectedAuthors" class="bg-gray-50 rounded-lg p-4 mb-6 hidden">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Selected Authors:</h4>
                        <div id="selectedAuthorsList" class="flex flex-wrap gap-2"></div>
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
                                <li>Select at least one author for the book</li>
                                <li>Publication year must be between 1900 and current year</li>
                                <li>Number of copies determines available inventory</li>
                                <li>Use the "Add New Author" button if an author is missing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form reset function
        function resetForm() {
            document.getElementById('bookForm').reset();
            updateSelectedAuthors();
            showTemporaryMessage('Form has been reset', 'blue');
        }

        // Update selected authors preview
        function updateSelectedAuthors() {
            const select = document.getElementById('author_ids');
            const selectedOptions = Array.from(select.selectedOptions);
            const preview = document.getElementById('selectedAuthors');
            const previewList = document.getElementById('selectedAuthorsList');
            
            previewList.innerHTML = '';
            
            if (selectedOptions.length > 0) {
                selectedOptions.forEach(option => {
                    const badge = document.createElement('div');
                    badge.className = 'bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full flex items-center';
                    badge.innerHTML = `
                        ${option.text}
                        <button type="button" onclick="deselectAuthor('${option.value}')" class="ml-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    `;
                    previewList.appendChild(badge);
                });
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        // Deselect author
        function deselectAuthor(authorId) {
            const select = document.getElementById('author_ids');
            const option = Array.from(select.options).find(opt => opt.value === authorId);
            if (option) {
                option.selected = false;
                updateSelectedAuthors();
            }
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
            const yearPublished = document.getElementById('year_published').value;
            const category = document.getElementById('category_id').value;
            const authors = document.getElementById('author_ids');
            const selectedAuthors = Array.from(authors.selectedOptions).length;
            
            if (!title || !yearPublished || !category || selectedAuthors === 0) {
                e.preventDefault();
                showTemporaryMessage('Please fill in all required fields', 'red');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set up authors selection listener
            document.getElementById('author_ids').addEventListener('change', updateSelectedAuthors);
            
            // Initialize selected authors preview
            updateSelectedAuthors();
            
            // Auto-focus title field
            document.getElementById('title').focus();
        });
    </script>
</body>
</html>