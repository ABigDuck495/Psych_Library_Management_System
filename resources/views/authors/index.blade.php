@extends('layouts.app')

@section('title', 'Authors Management - Psych Library')

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
    
    .add-author-btn {
        transition: all 0.3s ease;
    }
    
    .add-author-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
</style>
@endpush

@section('content')
<div class="flex-1 p-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Author Management</h1>
        </div>
        
        <div>
            <a href="{{ route('authors.create') }}" class="add-author-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                <i class="fas fa-plus-circle mr-2"></i>
                Add New Author
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Export Buttons -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('export.authors', 'all') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export All Authors
            </a>
            <a href="{{ route('export.authors', 'books') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export Book Authors
            </a>
            <a href="{{ route('export.authors', 'theses') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export Thesis Authors
            </a>
            <a href="{{ route('export.authors', 'prolific') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export Prolific Authors
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            
            <!-- First Name Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <select id="firstNameFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All First Names</option>
                    @foreach($authors->pluck('first_name')->unique()->sort() as $firstName)
                        <option value="{{ $firstName }}">{{ $firstName }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Last Name Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <select id="lastNameFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Last Names</option>
                    @foreach($authors->pluck('last_name')->unique()->sort() as $lastName)
                        <option value="{{ $lastName }}">{{ $lastName }}</option>
                    @endforeach
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

    <!-- Authors Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="authorsTableBody">
                    @forelse ($authors as $author)
                        <tr class="table-row-hover author-row" 
                            data-firstname="{{ strtolower($author->first_name) }}"
                            data-lastname="{{ strtolower($author->last_name) }}"
                            data-fullname="{{ strtolower($author->first_name . ' ' . $author->last_name) }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $author->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $author->first_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $author->last_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('authors.edit', $author->id) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 action-btn p-2 text-lg inline-flex items-center justify-center" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('authors.destroy', $author->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this author?');"
                                                class="text-red-600 hover:text-red-900 action-btn p-2 text-lg inline-flex items-center justify-center" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-user-edit text-5xl mb-4"></i>
                                    <h3 class="text-lg font-medium mb-2">No authors found</h3>
                                    <p class="mb-4">Get started by adding your first author</p>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('authors.create') }}" class="add-author-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            Add New Author
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

    <!-- Pagination would go here -->
</div>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const firstNameFilter = document.getElementById('firstNameFilter');
        const lastNameFilter = document.getElementById('lastNameFilter');
        const filterButton = document.getElementById('filterButton');
        const resetButton = document.getElementById('resetButton');
        const authorRows = document.querySelectorAll('.author-row');

        function filterAuthors() {
            const searchTerm = searchInput.value.toLowerCase();
            const firstNameValue = firstNameFilter.value.toLowerCase();
            const lastNameValue = lastNameFilter.value.toLowerCase();

            authorRows.forEach(row => {
                const firstname = row.getAttribute('data-firstname');
                const lastname = row.getAttribute('data-lastname');
                const fullname = row.getAttribute('data-fullname');

                const matchesSearch = fullname.includes(searchTerm);
                const matchesFirstName = !firstNameValue || firstname.includes(firstNameValue);
                const matchesLastName = !lastNameValue || lastname.includes(lastNameValue);

                row.style.display = (matchesSearch && matchesFirstName && matchesLastName) ? '' : 'none';
            });
        }

        filterButton.addEventListener('click', filterAuthors);
        
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            firstNameFilter.value = '';
            lastNameFilter.value = '';
            authorRows.forEach(row => row.style.display = '');
        });

        // Real-time search
        searchInput.addEventListener('input', filterAuthors);
    });
</script>
@endpush