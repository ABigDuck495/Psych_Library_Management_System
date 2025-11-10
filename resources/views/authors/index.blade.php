@extends('layouts.app')

@section('title', 'Authors Management - Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Authors Management</h1>
            <p class="text-gray-600 mt-2">Manage book and thesis authors</p>
        </div>
        
        <div class="flex space-x-3">
            <!-- Export Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="export-dropdown-btn bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                    <i class="fas fa-file-export mr-2"></i>
                    Export Authors
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
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Export Authors</h3>
                        <div class="space-y-1">
                            <a href="{{ route('export.authors', 'all') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                                <i class="fas fa-users mr-2 text-blue-600"></i>
                                All Authors
                            </a>
                            <a href="{{ route('export.authors', 'books') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition">
                                <i class="fas fa-book mr-2 text-green-600"></i>
                                Book Authors
                            </a>
                            <a href="{{ route('export.authors', 'theses') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition">
                                <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>
                                Thesis Authors
                            </a>
                            <a href="{{ route('export.authors', 'prolific') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                                <i class="fas fa-star mr-2 text-orange-600"></i>
                                Prolific Authors
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
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

    <!-- Search and Sort Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Authors</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search by name..." 
                           class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <!-- Sort Options -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select id="sortSelect" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="name_asc">Name (A-Z)</option>
                    <option value="name_desc">Name (Z-A)</option>
                    <option value="works_asc">Works Count (Low to High)</option>
                    <option value="works_desc">Works Count (High to Low)</option>
                    <option value="id_asc">ID (Oldest First)</option>
                    <option value="id_desc">ID (Newest First)</option>
                </select>
            </div>
            
            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button id="applySort" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex-1 transition">
                    Apply Sort
                </button>
                <button id="resetButton" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Authors Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-sort="id">
                            ID <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-sort="first_name">
                            First Name <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-sort="last_name">
                            Last Name <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort-header" data-sort="works">
                            Works Count <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="authorsTableBody">
                    @forelse ($authors as $author)
                        <tr class="table-row-hover author-row" 
                            data-id="{{ $author->id }}"
                            data-firstname="{{ strtolower($author->first_name) }}"
                            data-lastname="{{ strtolower($author->last_name) }}"
                            data-fullname="{{ strtolower($author->first_name . ' ' . $author->last_name) }}"
                            data-works-count="{{ $author->books_count + $author->theses_count }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $author->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $author->first_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $author->last_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center space-x-4">
                                    @if($author->books_count > 0)
                                        <span class="bg-green-100 text-green-800 works-badge">
                                            {{ $author->books_count }} Book{{ $author->books_count > 1 ? 's' : '' }}
                                        </span>
                                    @endif
                                    @if($author->theses_count > 0)
                                        <span class="bg-blue-100 text-blue-800 works-badge">
                                            {{ $author->theses_count }} Thesis{{ $author->theses_count > 1 ? 'es' : '' }}
                                        </span>
                                    @endif
                                    @if($author->books_count == 0 && $author->theses_count == 0)
                                        <span class="text-gray-400 text-xs italic">No works</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('authors.edit', $author->id) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 action-btn" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('authors.show', $author->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 action-btn" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('authors.destroy', $author->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-900 action-btn delete-btn" 
                                                title="Delete"
                                                data-author-name="{{ $author->first_name }} {{ $author->last_name }}"
                                                data-author-id="{{ $author->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-user-edit text-5xl mb-4"></i>
                                    <h3 class="text-lg font-medium mb-2">No authors found</h3>
                                    <p class="mb-4">No authors are currently available in the system</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($authors->count() > 0)
        <div class="mt-6">
            {{ $authors->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    .table-row-hover:hover {
        background-color: #f8fafc;
    }
    
    .action-btn {
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    .works-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .export-dropdown-btn {
        transition: all 0.3s ease;
    }
    
    .export-dropdown-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .sort-header {
        transition: all 0.2s ease;
        user-select: none;
    }
    
    .sort-header:hover {
        background-color: #f1f5f9;
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Filter and Sort functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const applySort = document.getElementById('applySort');
        const resetButton = document.getElementById('resetButton');
        const authorRows = document.querySelectorAll('.author-row');
        const sortHeaders = document.querySelectorAll('.sort-header');

        let currentSort = {
            field: 'first_name',
            direction: 'asc'
        };

        function sortAuthors() {
            const rowsArray = Array.from(authorRows);
            const searchTerm = searchInput.value.toLowerCase();

            // First filter by search term
            const filteredRows = rowsArray.filter(row => {
                const fullname = row.getAttribute('data-fullname');
                return fullname.includes(searchTerm);
            });

            // Then sort the filtered rows
            filteredRows.sort((a, b) => {
                let aValue, bValue;

                switch (currentSort.field) {
                    case 'id':
                        aValue = parseInt(a.getAttribute('data-id'));
                        bValue = parseInt(b.getAttribute('data-id'));
                        break;
                    case 'works':
                        aValue = parseInt(a.getAttribute('data-works-count'));
                        bValue = parseInt(b.getAttribute('data-works-count'));
                        break;
                    case 'first_name':
                        aValue = a.getAttribute('data-firstname');
                        bValue = b.getAttribute('data-firstname');
                        break;
                    case 'last_name':
                        aValue = a.getAttribute('data-lastname');
                        bValue = b.getAttribute('data-lastname');
                        break;
                    default:
                        aValue = a.getAttribute('data-firstname') + ' ' + a.getAttribute('data-lastname');
                        bValue = b.getAttribute('data-firstname') + ' ' + b.getAttribute('data-lastname');
                }

                if (currentSort.direction === 'asc') {
                    return aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
                } else {
                    return aValue > bValue ? -1 : aValue < bValue ? 1 : 0;
                }
            });

            // Update the table
            const tbody = document.getElementById('authorsTableBody');
            tbody.innerHTML = '';

            if (filteredRows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-search text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium mb-2">No authors found</h3>
                                <p class="mb-4">Try adjusting your search criteria</p>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                filteredRows.forEach(row => tbody.appendChild(row));
            }
        }

        function updateSortIcons() {
            sortHeaders.forEach(header => {
                const icon = header.querySelector('i');
                const field = header.getAttribute('data-sort');
                
                if (field === currentSort.field) {
                    icon.className = currentSort.direction === 'asc' ? 
                        'fas fa-sort-up ml-1' : 'fas fa-sort-down ml-1';
                } else {
                    icon.className = 'fas fa-sort ml-1';
                }
            });
        }

        // Apply sort from dropdown
        applySort.addEventListener('click', function() {
            const sortValue = sortSelect.value;
            
            switch (sortValue) {
                case 'name_asc':
                    currentSort = { field: 'first_name', direction: 'asc' };
                    break;
                case 'name_desc':
                    currentSort = { field: 'first_name', direction: 'desc' };
                    break;
                case 'works_asc':
                    currentSort = { field: 'works', direction: 'asc' };
                    break;
                case 'works_desc':
                    currentSort = { field: 'works', direction: 'desc' };
                    break;
                case 'id_asc':
                    currentSort = { field: 'id', direction: 'asc' };
                    break;
                case 'id_desc':
                    currentSort = { field: 'id', direction: 'desc' };
                    break;
            }
            
            updateSortIcons();
            sortAuthors();
        });

        // Header click sorting
        sortHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const field = this.getAttribute('data-sort');
                
                if (currentSort.field === field) {
                    // Toggle direction if same field
                    currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                } else {
                    // New field, default to ascending
                    currentSort.field = field;
                    currentSort.direction = 'asc';
                }
                
                updateSortIcons();
                sortAuthors();
            });
        });

        // Reset functionality
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            sortSelect.value = 'name_asc';
            currentSort = { field: 'first_name', direction: 'asc' };
            updateSortIcons();
            sortAuthors();
        });

        // Real-time search
        searchInput.addEventListener('input', sortAuthors);

        // SweetAlert for delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const authorName = this.getAttribute('data-author-name');
                const authorId = this.getAttribute('data-author-id');
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to delete <strong>${authorName}</strong>. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'swal2-confirm',
                        cancelButton: 'swal2-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the author',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        form.submit();
                    }
                });
            });
        });

        // Success message handling (if there was a successful deletion)
        @if(session('delete_success'))
        Swal.fire({
            title: 'Deleted!',
            text: '{{ session('delete_success') }}',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        @endif

        // Initialize sort icons
        updateSortIcons();
    });
</script>
@endpush
@endsection