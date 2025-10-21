<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - {{ $user->first_name }} {{ $user->last_name }} | Psych Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%);
        }
        
        .active-nav {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid white;
        }
        
        .info-card {
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .info-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
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
                        <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg active-nav">
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
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">User Details</h1>
                    <p class="text-gray-600 mt-2">View and manage user information</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Users
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

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">User Actions</h2>
                
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                        <i class="fas fa-edit mr-2"></i>
                        Edit User
                    </a>
                    
                    <a href="mailto:{{ $user->email }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                        <i class="fas fa-envelope mr-2"></i>
                        Send Message
                    </a>
                    
                    @if($user->account_status === 'Active')
                        <form action="{{ route('users.deactivate', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Deactivate this user?')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                                <i class="fas fa-ban mr-2"></i>
                                Deactivate Account
                            </button>
                        </form>
                    @else
                        <form action="{{ route('users.activate', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                                <i class="fas fa-user-check mr-2"></i>
                                Activate Account
                            </button>
                        </form>
                    @endif

                    <a href="#" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                        <i class="fas fa-history mr-2"></i>
                        View Full History
                    </a>
                </div>
            </div>

            <!-- User Information -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 info-card lg:col-span-2">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                        Basic Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">University ID</label>
                                <p class="text-gray-800 font-medium">{{ $user->university_id }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                                <p class="text-gray-800 font-medium">{{ $user->first_name }} {{ $user->last_name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Username</label>
                                <p class="text-gray-800 font-medium">{{ $user->username }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                                <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                                <p class="text-gray-800 font-medium">{{ $user->phone_number ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                                @if($user->role === 'super-admin')
                                    <span class="role-badge bg-purple-100 text-purple-800">Super Admin</span>
                                @elseif($user->role === 'admin')
                                    <span class="role-badge bg-red-100 text-red-800">Admin</span>
                                @elseif($user->role === 'librarian')
                                    <span class="role-badge bg-blue-100 text-blue-800">Librarian</span>
                                @else
                                    <span class="role-badge bg-green-100 text-green-800">User</span>
                                @endif
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Account Status</label>
                                @if($user->account_status === 'Active')
                                    <span class="status-badge bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="status-badge bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">User Type</label>
                                <div class="flex items-center mt-1">
                                    @if(isset($user->user_type) && $user->user_type === 'student')
                                        <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                                        <span class="text-gray-800 font-medium capitalize">Student</span>
                                    @elseif(isset($user->user_type) && $user->user_type === 'employee')
                                        <i class="fas fa-briefcase text-green-600 mr-2"></i>
                                        <span class="text-gray-800 font-medium capitalize">Employee</span>
                                    @else
                                        <i class="fas fa-user text-gray-600 mr-2"></i>
                                        <span class="text-gray-800 font-medium capitalize">{{ $user->user_type ?? 'Not Specified' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Profile Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-id-card mr-2 text-green-600"></i>
                        Profile
                    </h2>
                    
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                            <i class="fas fa-user text-blue-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</h3>
                        <p class="text-gray-600 capitalize">{{ $user->role }}</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Registration Date</span>
                            <span class="text-gray-800 font-medium">{{ $user->registration_date->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-500">Last Login</span>
                            <span class="text-gray-800 font-medium">
                                {{ $user->last_login_date ? $user->last_login_date->format('M d, Y H:i') : 'Never' }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-500">Account Created</span>
                            <span class="text-gray-800 font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-500">Last Updated</span>
                            <span class="text-gray-800 font-medium">{{ $user->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- User Type Specific Information -->
                @if(isset($user->user_type) && $user->user_type === 'student')
                <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-university mr-2 text-blue-600"></i>
                        Student Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Academic Program</label>
                            <p class="text-gray-800 font-medium">{{ $user->academic_program ?? 'Not Specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Major/Department</label>
                            <p class="text-gray-800 font-medium">{{ $user->major_department ?? 'Not Specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Year Level</label>
                            <p class="text-gray-800 font-medium">{{ $user->year_level ?? 'Not Specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Academic Standing</label>
                            <span class="status-badge bg-green-100 text-green-800">{{ $user->academic_standing ?? 'Good Standing' }}</span>
                        </div>
                    </div>
                </div>
                @elseif(isset($user->user_type) && $user->user_type === 'employee')
                <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-briefcase mr-2 text-green-600"></i>
                        Employee Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                            <p class="text-gray-800 font-medium">{{ $user->department ?? 'Not Specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Position Title</label>
                            <p class="text-gray-800 font-medium">{{ $user->position_title ?? 'Not Specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Employee ID</label>
                            <p class="text-gray-800 font-medium">{{ $user->employee_id ?? 'Not Specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Employment Status</label>
                            <span class="status-badge bg-blue-100 text-blue-800">{{ $user->employment_status ?? 'Active' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-gray-600"></i>
                        Additional Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">User Type</label>
                            <p class="text-gray-800 font-medium capitalize">{{ $user->user_type ?? 'Not Specified' }}</p>
                        </div>
                        
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-user-tag text-4xl mb-2"></i>
                            <p>No additional information available</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Activity Summary Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 info-card">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-purple-600"></i>
                        Activity Summary
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Currently Borrowed</span>
                            <span class="text-gray-800 font-medium">{{ $user->currently_borrowed_count ?? 0 }} items</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Reservations</span>
                            <span class="text-gray-800 font-medium">{{ $user->reservations_count ?? 0 }} items</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Overdue Items</span>
                            <span class="text-red-600 font-medium">{{ $user->overdue_items_count ?? 0 }} items</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Total Checkouts</span>
                            <span class="text-gray-800 font-medium">{{ $user->total_checkouts_count ?? 0 }} items</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Recent Activity</h3>
                        @if(isset($user->recent_activity) && count($user->recent_activity) > 0)
                            <ul class="space-y-2 text-sm">
                                @foreach($user->recent_activity as $activity)
                                    <li class="flex items-center">
                                        <i class="fas {{ $activity['icon'] }} {{ $activity['color'] }} mr-2"></i>
                                        <span>{{ $activity['description'] }} - {{ $activity['date'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-400 text-sm">No recent activity recorded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success message after 5 seconds
            const successMessage = document.querySelector('.bg-green-100');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>
</html>