<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Author - Psych Library</title>
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
                        <a href="{{ route('books.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
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
                        <a href="{{ route('authors.index') }}" class="flex items-center p-3 rounded-lg active-nav">
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
                    <h1 class="text-3xl font-bold text-gray-800">Edit Author</h1>
                    <p class="text-gray-600 mt-2">Update author information in the library system</p>
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

            <!-- Edit Author Form -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="max-w-2xl">
                    <form action="{{ route('authors.update', $author->id) }}" method="POST" id="authorForm">
                        @csrf
                        @method('PUT')

                        <!-- Form Header -->
                        <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                                <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Edit Author Information</h2>
                                <p class="text-gray-600 text-sm">Update the author's details below</p>
                            </div>
                        </div>

                        <!-- Author ID Display -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                <span class="font-medium">Author ID:</span>
                                <span class="ml-2 font-mono bg-white px-2 py-1 rounded border">{{ $author->id }}</span>
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
                                        value="{{ old('first_name', $author->first_name) }}" 
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
                                        value="{{ old('last_name', $author->last_name) }}" 
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
                                    onclick="resetToOriginal()" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
                                >
                                    <i class="fas fa-history mr-2"></i>
                                    Reset to Original
                                </button>
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                                >
                                    <i class="fas fa-save mr-2"></i>
                                    Update Author
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Last Updated -->
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Last Updated</p>
                            <p class="text-blue-900">{{ $author->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-plus text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-700 font-medium">Created Date</p>
                            <p class="text-green-900">{{ $author->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('authors.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                            <i class="fas fa-list text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">View All Authors</p>
                            <p class="text-sm text-gray-500">Browse complete authors list</p>
                        </div>
                    </a>
                    <a href="{{ route('authors.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-plus text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Add New Author</p>
                            <p class="text-sm text-gray-500">Create another author</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store original values for reset functionality
        const originalValues = {
            first_name: "{{ $author->first_name }}",
            last_name: "{{ $author->last_name }}"
        };

        // Reset form to original values
        function resetToOriginal() {
            document.getElementById('first_name').value = originalValues.first_name;
            document.getElementById('last_name').value = originalValues.last_name;
            
            showTemporaryMessage('Form reset to original values', 'blue');
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
            const bgColor = type === 'red' ? 'red' : 'blue';
            messageDiv.className = `fixed top-4 right-4 bg-${bgColor}-100 border border-${bgColor}-400 text-${bgColor}-700 px-4 py-3 rounded-lg shadow-lg z-50`;
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
            
            // Check if values have actually changed
            if (firstName === originalValues.first_name && lastName === originalValues.last_name) {
                e.preventDefault();
                showTemporaryMessage('No changes detected', 'blue');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
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
            
            // Highlight if values are different from original
            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');
            
            function checkForChanges() {
                if (firstNameInput.value !== originalValues.first_name || 
                    lastNameInput.value !== originalValues.last_name) {
                    showTemporaryMessage('You have unsaved changes', 'blue');
                }
            }
            
            firstNameInput.addEventListener('change', checkForChanges);
            lastNameInput.addEventListener('change', checkForChanges);
        });
    </script>
</body>
</html>