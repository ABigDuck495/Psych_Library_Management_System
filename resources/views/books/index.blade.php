@extends('layouts.app')

@section('title', 'Books Management - Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Books Management</h1>
            <p class="text-gray-600 mt-2">Manage library books and publications</p>
        </div>
        
        <div class="flex space-x-3">
            <!-- Export Dropdown - Only show for admin/librarian -->
            @if(in_array(auth()->user()->role, ['admin', 'librarian']))
            <div class="relative" x-data="{ open: false, categoryOpen: false }">
                <button @click="open = !open" 
                        class="export-dropdown-btn bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                    <i class="fas fa-file-export mr-2"></i>
                    Export Books
                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div x-show="open" 
                     @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg z-10 border border-gray-200 py-2">
                    <!-- Export All -->
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Export All Books</h3>
                        <div class="space-y-1">
                            <a href="{{ route('export.books') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                                <i class="fas fa-file-export mr-2 text-blue-600"></i>
                                All Books
                            </a>
                        </div>
                    </div>
                    
                    <!-- Export by Category Dropdown -->
                    <div class="px-4 py-2 border-b border-gray-100">
                        <div class="relative">
                            <button @click="categoryOpen = !categoryOpen" 
                                    class="w-full flex justify-between items-center px-3 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition">
                                <div class="flex items-center">
                                    <i class="fas fa-folder mr-2 text-purple-600"></i>
                                    Export by Category
                                </div>
                                <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'transform rotate-180': categoryOpen }"></i>
                            </button>
                            
                            <!-- Category Dropdown Menu -->
                            <div x-show="categoryOpen" 
                                 @click.away="categoryOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 mt-1 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 max-h-60 overflow-y-auto z-20">
                                <div class="space-y-1">
                                    <a href="{{ route('export.books', ['category' => 'Agriculture']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Agriculture
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Auxiliary Sciences of History']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Auxiliary Sciences of History
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Bibliography, Library Science, General Information Resources']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Bibliography & Library Science
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Education']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Education
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Fine Arts']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Fine Arts
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'General Works']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        General Works
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Geography, Anthropology, Recreation']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Geography & Anthropology
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'History of the Americas (Local and Latin America)']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        History (Latin America)
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'History of the Americas (United States)']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        History (United States)
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Language and Literature']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Language & Literature
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Law']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Law
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Medicine']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Medicine
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Military Science']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Military Science
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Music']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Music
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Naval Science']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Naval Science
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Philosophy, Psychology, Religion']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Philosophy & Psychology
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Political Science']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Political Science
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Science']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Science
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Social Sciences']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Social Sciences
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'Technology']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        Technology
                                    </a>
                                    <a href="{{ route('export.books', ['category' => 'World Sciences']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                        World Sciences
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Export by Year Form -->
                    <div class="px-4 py-2">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Export by Year</h3>
                        <form action="{{ route('export.books') }}" method="GET" class="space-y-2">
                            <select name="year_published" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Years</option>
                                @php $currentYear = date('Y'); @endphp
                                @for($y = $currentYear; $y >= 1900; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                            <button type="submit" 
                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-lg text-sm font-medium flex items-center justify-center transition">
                                <i class="fas fa-download mr-2"></i>
                                Export by Year
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Add Book Button - Only show for admin/librarian -->
            @if(in_array(auth()->user()->role, ['admin', 'librarian']))
            <a href="{{ route('books.create') }}" class="add-book-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-medium flex items-center transition shadow-md">
                <i class="fas fa-plus-circle mr-2"></i>
                Add New Book
            </a>
            @endif
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
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
                    <option value="Agriculture">Agriculture</option>
                    <option value="Auxiliary Sciences of History">Auxiliary Sciences of History</option>
                    <option value="Bibliography, Library Science, General Information Resources">Bibliography, Library Science, General Information Resources</option>
                    <option value="Education">Education</option>
                    <option value="Fine Arts">Fine Arts</option>
                    <option value="General Works">General Works</option>
                    <option value="Geography, Anthropology, Recreation">Geography, Anthropology, Recreation</option>
                    <option value="History of the Americas (Local and Latin America)">History of the Americas (Local and Latin America)</option>
                    <option value="History of the Americas (United States)">History of the Americas (United States)</option>
                    <option value="Language and Literature">Language and Literature</option>
                    <option value="Law">Law</option>
                    <option value="Medicine">Medicine</option>
                    <option value="Military Science">Military Science</option>
                    <option value="Music">Music</option>
                    <option value="Naval Science">Naval Science</option>
                    <option value="Philosophy, Psychology, Religion">Philosophy, Psychology, Religion</option>
                    <option value="Political Science">Political Science</option>
                    <option value="Science">Science</option>
                    <option value="Social Sciences">Social Sciences</option>
                    <option value="Technology">Technology</option>
                    <option value="World Sciences">World Sciences</option>
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
                                @if(strlen($book->description) > 200)
                                    <a href="{{ route('books.show', $book->id) }}" 
                                       class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none inline-flex items-center">
                                        Read full description
                                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
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
                                    @if(in_array(auth()->user()->role, ['admin', 'librarian']))
                                        <a href="{{ route('books.edit', $book->id) }}" 
                                        class="text-yellow-600 hover:text-yellow-900 action-btn" 
                                        title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('books.show', $book->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 action-btn" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(in_array(auth()->user()->role, ['admin', 'librarian']))
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
                                    @endif
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
                                        @if(in_array(auth()->user()->role, ['admin', 'librarian']))
                                        <a href="{{ route('books.create') }}" class="add-book-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            Add New Book
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

   
</div>

@push('styles')
<style>
    .description-preview {
        display: -webkit-box;
        line-clamp: 3;
        -webkit-line-clamp: 3;
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
    
    .add-book-btn {
        transition: all 0.3s ease;
    }
    
    .add-book-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .export-dropdown-btn {
        transition: all 0.3s ease;
    }
    
    .export-dropdown-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* Category dropdown scroll */
    .max-h-60 {
        max-height: 15rem;
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
    }
    
    .pagination li {
        margin: 0 4px;
    }
    
    .pagination li a, 
    .pagination li span {
        display: inline-block;
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination li a:hover {
        background-color: #f3f4f6;
    }
    
    .pagination li.active span {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .pagination li.disabled span {
        color: #9ca3af;
        background-color: #f9fafb;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script>
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
@endpush
@endsection