<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authors Management - Psych Library</title>
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
        
        .highlight {
            background-color: #fef3c7;
            padding: 2px 4px;
            border-radius: 4px;
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
        <div id="mainContent" data-authors-count="{{ $authors->count() }}" class="flex-1 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Author Management</h1>
                    <p class="text-gray-600 mt-2">Manage all authors in the library system</p>
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
            
            <!-- Success Message -->
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
            
            <!-- Search and Add Author Section -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="w-full md:w-1/2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="searchInput" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Search authors by name..."
                            >
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('authors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                            <i class="fas fa-plus mr-2"></i>
                            Add New Author
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Authors Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">Authors List</h2>
                    <p class="text-gray-600 text-sm mt-1" id="authorCount">Showing {{ $authors->count() }} authors</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="authorsTableBody" class="bg-white divide-y divide-gray-200">
                            @foreach ($authors as $author)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $author->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 author-first-name">{{ $author->first_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 author-last-name">{{ $author->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('authors.edit', $author->id) }}" class="text-blue-600 hover:text-blue-900 transition">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <form action="{{ route('authors.destroy', $author->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition">
                                                    <i class="fas fa-trash mr-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div id="emptyState" class="hidden p-12 text-center">
                    <div class="mx-auto w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="fas fa-user-edit text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No authors found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your search or add a new author.</p>
                    <a href="{{ route('authors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Author
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fuzzy search function
        function fuzzySearch(query, text) {
            query = query.toLowerCase();
            text = text.toLowerCase();
            
            let queryIndex = 0;
            let textIndex = 0;
            
            while (textIndex < text.length) {
                if (text[textIndex] === query[queryIndex]) {
                    queryIndex++;
                    if (queryIndex === query.length) {
                        return true;
                    }
                }
                textIndex++;
            }
            
            return false;
        }

        // Highlight matching text
        function highlightText(text, query) {
            if (!query) return text;
            
            const regex = new RegExp(`(${query})`, 'gi');
            return text.replace(regex, '<span class="highlight">$1</span>');
        }

        // Search functionality
        function handleSearch() {
            const searchInput = document.getElementById('searchInput');
            const query = searchInput.value.trim();
            const tableBody = document.getElementById('authorsTableBody');
            const rows = tableBody.getElementsByTagName('tr');
            const emptyState = document.getElementById('emptyState');
            const authorCount = document.getElementById('authorCount');
            
            let visibleCount = 0;
            
            if (query === '') {
                // Show all rows
                for (let row of rows) {
                    row.style.display = '';
                    // Remove any existing highlights
                    const firstNameCell = row.querySelector('.author-first-name');
                    const lastNameCell = row.querySelector('.author-last-name');
                    
                    if (firstNameCell && lastNameCell) {
                        firstNameCell.innerHTML = firstNameCell.textContent;
                        lastNameCell.innerHTML = lastNameCell.textContent;
                    }
                }
                visibleCount = rows.length;
            } else {
                // Filter rows based on search
                for (let row of rows) {
                    const firstNameCell = row.querySelector('.author-first-name');
                    const lastNameCell = row.querySelector('.author-last-name');
                    
                    if (firstNameCell && lastNameCell) {
                        const firstName = firstNameCell.textContent;
                        const lastName = lastNameCell.textContent;
                        const fullName = `${firstName} ${lastName}`.toLowerCase();
                        
                        if (fuzzySearch(query, fullName)) {
                            row.style.display = '';
                            visibleCount++;
                            
                            // Highlight matching text
                            firstNameCell.innerHTML = highlightText(firstName, query);
                            lastNameCell.innerHTML = highlightText(lastName, query);
                        } else {
                            row.style.display = 'none';
                        }
                    }
                }
            }
            
            // Update author count and show/hide empty state
            authorCount.textContent = `Showing ${visibleCount} authors`;
            
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
                tableBody.parentElement.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                tableBody.parentElement.classList.remove('hidden');
            }
        }

        // Delete confirmation
        function confirmDelete() {
            return confirm('Are you sure you want to delete this author?');
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set up search input event listener
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', handleSearch);
            
            // Check if we should show empty state initially (read from data-attribute to avoid Blade in JS)
            const mainContent = document.getElementById('mainContent');
            const authorsCount = parseInt(mainContent?.dataset?.authorsCount || 0, 10);
            if (authorsCount === 0) {
                document.getElementById('emptyState').classList.remove('hidden');
                document.getElementById('authorsTableBody').parentElement.classList.add('hidden');
            }
        });
    </script>
</body>
</html>