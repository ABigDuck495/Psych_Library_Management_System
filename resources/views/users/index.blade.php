@extends('layouts.app')

@section('title', 'User Management - Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
            <p class="text-gray-600 mt-2">Manage library users, roles, and permissions</p>
        </div>
        
        <div class="flex space-x-3">
            <!-- Export Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="export-dropdown-btn bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                    <i class="fas fa-file-export mr-2"></i>
                    Export Users
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
                    <!-- Export by Role -->
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Export by Role</h3>
                        <div class="space-y-1">
                            <a href="{{ route('export.users', 'rawr') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                                <i class="fas fa-users mr-2 text-blue-600"></i>
                                All Users
                            </a>
                            <a href="{{ route('export.users', 'user') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition">
                                <i class="fas fa-user mr-2 text-green-600"></i>
                                Regular Users
                            </a>
                            <a href="{{ route('export.users', 'librarian') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition">
                                <i class="fas fa-book-reader mr-2 text-blue-600"></i>
                                Librarians
                            </a>
                            <a href="{{ route('export.users', 'admin') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-lg transition">
                                <i class="fas fa-shield-alt mr-2 text-red-600"></i>
                                Admins
                            </a>
                        </div>
                    </div>
                    
                    <!-- Export by Status -->
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Export by Status</h3>
                        <div class="space-y-1">
                            <a href="{{ route('export.users', 'active') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition">
                                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                Active Users
                            </a>
                            <a href="{{ route('export.users', 'inactive') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-lg transition">
                                <i class="fas fa-ban mr-2 text-red-600"></i>
                                Inactive Users
                            </a>
                        </div>
                    </div>
                    
                    <!-- Export by Type -->
                    <div class="px-4 py-2">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Export by Type</h3>
                        <div class="space-y-1">
                            <a href="{{ route('export.users', 'student') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition">
                                <i class="fas fa-graduation-cap mr-2 text-indigo-600"></i>
                                Students
                            </a>
                            <a href="{{ route('export.users', 'employee') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                                <i class="fas fa-briefcase mr-2 text-orange-600"></i>
                                Employees
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add User Button -->
            @if (Auth::user() === 'admin')
                <a href="{{ route('users.create') }}" class="add-user-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-medium flex items-center transition shadow-md">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add New User
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
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Users</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchInput" placeholder="Name, username, or email..." 
                           class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <!-- Role Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select id="roleFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Roles</option>
                    <option value="user">User</option>
                    <option value="librarian">Librarian</option>
                    <option value="admin">Admin</option>
                    <option value="super-admin">Super Admin</option>
                </select>
            </div>
            
            <!-- User Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">User Type</label>
                <select id="typeFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="student">Student</option>
                    <option value="employee">Employee</option>
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
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

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">University ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                    @forelse ($users as $user)
                        <tr class="table-row-hover user-row" 
                            data-name="{{ strtolower($user->first_name . ' ' . $user->last_name) }}"
                            data-username="{{ strtolower($user->username) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-role="{{ strtolower($user->role) }}"
                            data-type="{{ strtolower($user->user_type) }}"
                            data-status="{{ strtolower($user->account_status) }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->university_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'super-admin')
                                    <span class="role-badge bg-purple-100 text-purple-800">Super Admin</span>
                                @elseif($user->role === 'admin')
                                    <span class="role-badge bg-red-100 text-red-800">Admin</span>
                                @elseif($user->role === 'librarian')
                                    <span class="role-badge bg-blue-100 text-blue-800">Librarian</span>
                                @else
                                    <span class="role-badge bg-green-100 text-green-800">User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">{{ $user->user_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->account_status === 'Active')
                                    <span class="status-badge bg-green-100 text-green-800 flex items-center w-fit">
                                        <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="status-badge bg-red-100 text-red-800 flex items-center w-fit">
                                        <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="text-blue-600 hover:text-blue-900 action-btn" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @can('manage-users')
                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 action-btn" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($user->account_status === 'Active')
                                            <form action="{{ route('users.deactivate', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        onclick="return confirm('Deactivate this user?')"
                                                        class="text-orange-600 hover:text-orange-900 action-btn" 
                                                        title="Deactivate">
                                                    <i class="fas fa-user-slash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('users.activate', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 action-btn" 
                                                        title="Activate">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Delete this user permanently?')"
                                                    class="text-red-600 hover:text-red-900 action-btn" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        @if (Auth::user() === 'admin')
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-users text-5xl mb-4"></i>
                                        <h3 class="text-lg font-medium mb-2">No users found</h3>
                                        <p class="mb-4">Get started by adding your first user</p>
                                        <div class="flex space-x-3">
                                            <a href="{{ route('users.create') }}" class="add-user-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                                                <i class="fas fa-user-plus mr-2"></i>
                                                Add New User
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination would go here -->
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
    
    .role-badge, .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .add-user-btn {
        transition: all 0.3s ease;
    }
    
    .add-user-btn:hover {
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const typeFilter = document.getElementById('typeFilter');
        const statusFilter = document.getElementById('statusFilter');
        const filterButton = document.getElementById('filterButton');
        const resetButton = document.getElementById('resetButton');
        const userRows = document.querySelectorAll('.user-row');

        function filterUsers() {
            const searchTerm = searchInput.value.toLowerCase();
            const roleValue = roleFilter.value.toLowerCase();
            const typeValue = typeFilter.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();

            userRows.forEach(row => {
                const name = row.getAttribute('data-name');
                const username = row.getAttribute('data-username');
                const email = row.getAttribute('data-email');
                const role = row.getAttribute('data-role');
                const type = row.getAttribute('data-type');
                const status = row.getAttribute('data-status');

                const matchesSearch = name.includes(searchTerm) || username.includes(searchTerm) || email.includes(searchTerm);
                const matchesRole = !roleValue || role.includes(roleValue);
                const matchesType = !typeValue || type.includes(typeValue);
                const matchesStatus = !statusValue || status.includes(statusValue);

                row.style.display = (matchesSearch && matchesRole && matchesType && matchesStatus) ? '' : 'none';
            });
        }

        filterButton.addEventListener('click', filterUsers);
        
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            roleFilter.value = '';
            typeFilter.value = '';
            statusFilter.value = '';
            userRows.forEach(row => row.style.display = '');
        });

        // Real-time search
        searchInput.addEventListener('input', filterUsers);
    });
</script>
@endpush
@endsection