<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Psych Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
                <p class="text-gray-600 mt-2">Manage library users, roles and permissions</p>
            </div>
            
            @can('manage-users')
            <div>
                <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add New User
                </a>
            </div>
            @endcan
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}" 
                                   class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Roles</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="librarian" {{ request('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super-admin" {{ request('role') == 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User Type</label>
                        <select name="user_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Types</option>
                            <option value="student" {{ request('user_type') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="employee" {{ request('user_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="account_status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="Active" {{ request('account_status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ request('account_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex-1 transition">
                            Filter
                        </button>
                        <a href="{{ route('users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Users Table -->
        @if($users->count() > 0)
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
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="table-row-hover">
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
                                        <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-900 action-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @can('manage-users')
                                            <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 action-btn" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @if($user->account_status === 'Active')
                                                <form action="{{ route('users.deactivate', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" onclick="return confirm('Deactivate this user?')" class="text-orange-600 hover:text-orange-900 action-btn" title="Deactivate">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('users.activate', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 action-btn" title="Activate">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Delete this user permanently?')" class="text-red-600 hover:text-red-900 action-btn" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-medium text-gray-700 mb-2">No users found</h3>
                <p class="text-gray-500 mb-6">Try adjusting your search or filter to find what you're looking for.</p>
                @can('manage-users')
                <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add New User
                </a>
                @endcan
            </div>
        @endif
    </div>

    <!-- Custom Pagination Styles -->
    <style>
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
</body>
</html>