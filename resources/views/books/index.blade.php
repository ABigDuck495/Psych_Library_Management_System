<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Management - Psych Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .description-preview {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .table-row-hover:hover {
            background-color: #f8fafc;
        }
        
        .action-btn {
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-1px);
        }
        
        .category-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .back-btn {
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            transform: translateX(-3px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-4">
                <!-- Back to Dashboard Button -->
                <a href="{{ route('index') }}" class="back-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Books Management</h1>
                    <p class="text-gray-600 mt-2">Manage and organize book collection in the library</p>
                </div>
            </div>
            
            <div>
                <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Add New Book
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Books</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchInput" placeholder="Title, author, or description..." 
                               class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="categoryFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        <option value="Psychology">Psychology</option>
                        <option value="Research">Research</option>
                        <option value="Academic">Academic</option>
                        <option value="Textbook">Textbook</option>
                    </select>
                </div>
                
                <!-- Year Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year Published</label>
                    <select id="yearFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Years</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                    </select>
                </div>
                
                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button id="filterButton" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex-1 transition">
                        Apply Filters
                    </button>
                    <button id="resetButton" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Books Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Published</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Authors</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="booksTableBody">
                        @forelse ($books as $book)
                            <tr class="table-row-hover book-row" 
                                data-title="{{ strtolower($book->title) }}"
                                data-category="{{ strtolower($book->category->category_name ?? '') }}"
                                data-year="{{ $book->year_published }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $book->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs">{{ $book->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-md">
                                    <div class="description-preview">{{ $book->description }}</div>
                                    @if(strlen($book->description) > 150)
                                        <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                                onclick="toggleDescription('{{ $book->id }}')">
                                            Show more
                                        </button>
                                        <div id="full-description-{{ $book->id }}" class="hidden mt-2 text-sm text-gray-600">
                                            {{ $book->description }}
                                            <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                                    onclick="toggleDescription('{{ $book->id }}')">
                                                Show less
                                            </button>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="bg-blue-100 text-blue-800 category-badge">
                                        {{ $book->year_published }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($book->category)
                                        <span class="bg-purple-100 text-purple-800 category-badge">
                                            {{ $book->category->category_name }}
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 category-badge">
                                            Uncategorized
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($book->authors->isNotEmpty())
                                        <div class="space-y-1">
                                            @foreach ($book->authors as $author)
                                                <div class="flex items-center">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                    <span>{{ $author->first_name }} {{ $author->last_name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">No authors linked</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('books.edit', $book->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 action-btn" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <a href="{{ route('books.show', $book->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 action-btn" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this book?');"
                                                    class="text-red-600 hover:text-red-900 action-btn" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-book text-5xl mb-4"></i>
                                        <h3 class="text-lg font-medium mb-2">No books found</h3>
                                        <p class="mb-4">Get started by adding your first book</p>
                                        <div class="flex space-x-3">
                                            <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition">
                                                <i class="fas fa-plus-circle mr-2"></i>
                                                Add New Book
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination and Bottom Navigation -->
        @if($books->count() > 0)
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Showing {{ $books->count() }} book{{ $books->count() > 1 ? 's' : '' }}
                </div>
                
                <div class="flex space-x-3">
                    <!-- Back to Dashboard Button (Bottom) -->
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                    
                    <!-- Simple pagination placeholder -->
                    <div class="flex space-x-2">
                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded text-sm transition">
                            Previous
                        </button>
                        <button class="bg-blue-600 text-white px-3 py-2 rounded text-sm">
                            1
                        </button>
                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded text-sm transition">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        @else
            <!-- Additional Back Button for Empty State -->
            <div class="mt-6 flex justify-center">
                <a href="{{ route('index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        @endif
    </div>

    <script>
        // Toggle description visibility
        function toggleDescription(bookId) {
            const element = document.getElementById(`full-description-${bookId}`);
            if (element) {
                element.classList.toggle('hidden');
                const button = element.previousElementSibling;
                if (button && button.tagName === 'BUTTON') {
                    button.textContent = element.classList.contains('hidden') ? 'Show more' : 'Show less';
                }
            }
        }

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const yearFilter = document.getElementById('yearFilter');
            const filterButton = document.getElementById('filterButton');
            const resetButton = document.getElementById('resetButton');
            const bookRows = document.querySelectorAll('.book-row');

            function filterBooks() {
                const searchTerm = searchInput.value.toLowerCase();
                const categoryValue = categoryFilter.value.toLowerCase();
                const yearValue = yearFilter.value;

                bookRows.forEach(row => {
                    const title = row.getAttribute('data-title');
                    const category = row.getAttribute('data-category');
                    const year = row.getAttribute('data-year');

                    const matchesSearch = title.includes(searchTerm);
                    const matchesCategory = !categoryValue || category.includes(categoryValue);
                    const matchesYear = !yearValue || year === yearValue;

                    row.style.display = (matchesSearch && matchesCategory && matchesYear) ? '' : 'none';
                });
            }

            filterButton.addEventListener('click', filterBooks);
            
            resetButton.addEventListener('click', function() {
                searchInput.value = '';
                categoryFilter.value = '';
                yearFilter.value = '';
                bookRows.forEach(row => row.style.display = '');
            });

            // Real-time search
            searchInput.addEventListener('input', filterBooks);
        });
    </script>
</body>
</html>