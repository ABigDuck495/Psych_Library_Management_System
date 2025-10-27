@extends('layouts.app')

@section('title', 'User Dashboard - Psych Library')

@push('styles')
<style>
    .content-preview {
        display: -webkit-box;
        /* Standard property for compatibility */
        line-clamp: 3;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .table-row-hover:hover {
        background-color: #f8fafc;
    }
    
    .resource-card {
        transition: all 0.3s ease;
    }
    
    .resource-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .tab-active {
        border-bottom: 3px solid #3b82f6;
        color: #3b82f6;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="flex-1 p-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Welcome to Your Library Dashboard</h1>
            <p class="text-gray-600 mt-2">Access and manage your library resources</p>
        </div>
        <!-- Removed notification and message icons -->
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Pending Requests -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Pending Requests</p>
                    <h3 class="text-2xl font-bold mt-2">{{ $pendingCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
            <p class="text-yellow-500 text-sm mt-4">
                <i class="fas fa-info-circle mr-1"></i> Requests awaiting approval
            </p>
        </div>

        <!-- Currently Borrowed -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Currently Borrowed</p>
                    <h3 class="text-2xl font-bold mt-2">{{ $borrowedCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-blue-500 text-sm mt-4">
                <i class="fas fa-info-circle mr-1"></i> Books & theses you currently have
            </p>
        </div>

        <!-- Overdue Items -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm">Overdue</p>
                    <h3 class="text-2xl font-bold mt-2">{{ $overdueCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
            <p class="text-red-500 text-sm mt-4">
                <i class="fas fa-info-circle mr-1"></i> Items past their due date
            </p>
        </div>

    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search titles, authors, or keywords..." 
                           class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <!-- Resource Type Tabs -->
            <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                <button id="allTab" class="tab-active px-4 py-2 rounded-md transition">All Resources</button>
                <button id="thesesTab" class="px-4 py-2 rounded-md transition">Theses</button>
                <button id="booksTab" class="px-4 py-2 rounded-md transition">Books</button>
            </div>
        </div>
    </div>

    <!-- Resources Container -->
    <div id="resourcesContainer">
        <!-- Theses Section -->
        <div id="thesesSection" class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                    Academic Theses
                </h2>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $theses->count() }} {{ Str::plural('thesis', $theses->count()) }}
                </span>
            </div>

            @if($theses->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($theses as $thesis)
                        <div class="resource-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">Thesis</span>
                                <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded">{{ $thesis->year_published }}</span>
                            </div>
                            
                            <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $thesis->title }}</h3>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm content-preview">{{ $thesis->abstract }}</p>
                                @if(strlen($thesis->abstract) > 150)
                                    <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                            onclick="toggleContent('thesis-abstract-{{ $thesis->id }}')">
                                        Show more
                                    </button>
                                    <div id="thesis-abstract-{{ $thesis->id }}" class="hidden mt-2 text-sm text-gray-600">
                                        {{ $thesis->abstract }}
                                        <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                                onclick="toggleContent('thesis-abstract-{{ $thesis->id }}')">
                                            Show less
                                        </button>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                    <i class="fas fa-building mr-2 text-gray-400"></i>
                                    {{ $thesis->department }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2 text-gray-400"></i>
                                    @if($thesis->authors->isNotEmpty())
                                        {{ $thesis->authors->count() }} {{ Str::plural('author', $thesis->authors->count()) }}
                                    @else
                                        No authors linked
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <div class="text-sm text-gray-500">
                                    ID: {{ $thesis->id }}
                                </div>
                                <a href="{{ route('theses.show', $thesis->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <i class="fas fa-file-alt text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No theses available</h3>
                    <p class="text-gray-500">Check back later for new academic theses.</p>
                </div>
            @endif
        </div>

        <!-- Books Section -->
        <div id="booksSection">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-book text-green-600 mr-3"></i>
                    Book Collection
                </h2>
                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $books->count() }} {{ Str::plural('book', $books->count()) }}
                </span>
            </div>

            @if($books->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($books as $book)
                        <div class="resource-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">Book</span>
                                <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded">{{ $book->year_published }}</span>
                            </div>
                            
                            <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $book->title }}</h3>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 text-sm content-preview">{{ $book->description }}</p>
                                @if(strlen($book->description) > 150)
                                    <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                            onclick="toggleContent('book-desc-{{ $book->id }}')">
                                        Show more
                                    </button>
                                    <div id="book-desc-{{ $book->id }}" class="hidden mt-2 text-sm text-gray-600">
                                        {{ $book->description }}
                                        <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                                onclick="toggleContent('book-desc-{{ $book->id }}')">
                                            Show less
                                        </button>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                    <i class="fas fa-tag mr-2 text-gray-400"></i>
                                    {{ $book->category->category_name ?? 'Uncategorized' }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2 text-gray-400"></i>
                                    @if($book->authors->isNotEmpty())
                                        {{ $book->authors->count() }} {{ Str::plural('author', $book->authors->count()) }}
                                    @else
                                        No authors linked
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <div class="text-sm text-gray-500">
                                    ID: {{ $book->id }}
                                </div>
                                <a href="{{ route('books.show', $book->id) }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <i class="fas fa-book text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No books available</h3>
                    <p class="text-gray-500">Check back later for new book additions.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle content visibility (abstract/description)
    function toggleContent(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.classList.toggle('hidden');
            const button = element.previousElementSibling;
            if (button && button.tagName === 'BUTTON') {
                button.textContent = element.classList.contains('hidden') ? 'Show more' : 'Show less';
            }
        }
    }

    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const allTab = document.getElementById('allTab');
        const thesesTab = document.getElementById('thesesTab');
        const booksTab = document.getElementById('booksTab');
        const thesesSection = document.getElementById('thesesSection');
        const booksSection = document.getElementById('booksSection');
        const searchInput = document.getElementById('searchInput');

        function setActiveTab(tab) {
            // Remove active class from all tabs
            [allTab, thesesTab, booksTab].forEach(t => t.classList.remove('tab-active'));
            // Add active class to clicked tab
            tab.classList.add('tab-active');
        }

        function filterResources() {
            const searchTerm = searchInput.value.toLowerCase();
            const isThesesTabActive = thesesTab.classList.contains('tab-active');
            const isBooksTabActive = booksTab.classList.contains('tab-active');
            
            // Show/hide sections based on active tab
            if (isThesesTabActive) {
                thesesSection.style.display = 'block';
                booksSection.style.display = 'none';
            } else if (isBooksTabActive) {
                thesesSection.style.display = 'none';
                booksSection.style.display = 'block';
            } else {
                thesesSection.style.display = 'block';
                booksSection.style.display = 'block';
            }

            // Simple search filter (you can enhance this with more complex logic)
            const resourceCards = document.querySelectorAll('.resource-card');
            resourceCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                card.style.display = shouldShow ? 'block' : 'none';
            });
        }

        // Tab click events
        allTab.addEventListener('click', () => {
            setActiveTab(allTab);
            filterResources();
        });

        thesesTab.addEventListener('click', () => {
            setActiveTab(thesesTab);
            filterResources();
        });

        booksTab.addEventListener('click', () => {
            setActiveTab(booksTab);
            filterResources();
        });

        // Search input event
        searchInput.addEventListener('input', filterResources);

        // Initialize
        filterResources();
    });
</script>
@endpush