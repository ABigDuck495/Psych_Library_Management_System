<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Psych Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
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
            background: linear-gradient(135deg, #f0fdf4 0%, #fbfbfbff 100%);
        }
    </style>
</head>
<body class="dashboard-bg min-h-screen">
    <div class="flex">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <!-- Logo and App Name -->
            <div class="sidebar-logo">
                <div class="logo-icon">
                    <i class="fas fa-book text-green-800 text-lg"></i>
                </div>
                <h1 class="logo-text">Psych Library</h1>
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
            <div class="nav-section">
                <h2 class="section-title">Main Navigation</h2>
                <ul class="nav-menu">
                    <li>
                        <a href="#" class="nav-item active">
                            <i class="fas fa-home nav-icon"></i>
                            Dashboard
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Management Sections -->
            <div class="nav-section">
                <h2 class="section-title">Management</h2>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ route('users.index') }}" class="nav-item">
                            <i class="fas fa-users nav-icon"></i>
                            User Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}" class="nav-item">
                            <i class="fas fa-book-open nav-icon"></i>
                            Book Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('theses.index') }}" class="nav-item">
                            <i class="fas fa-file-alt nav-icon"></i>
                            Thesis Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('authors.index') }}" class="nav-item">
                            <i class="fas fa-user-edit nav-icon"></i>
                            Author Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}" class="nav-item">
                            <i class="fas fa-exchange-alt nav-icon"></i>
                            Transactions
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Logout Button -->
            <div class="logout-section">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8 overflow-y-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Welcome to Psych Library Management System</h1>
                    <p class="text-gray-600 mt-2">Manage library resources, users, and transactions efficiently</p>
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
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Books</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $totalBooks ?? '12,458' }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Active Users</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $activeUsers ?? '2,847' }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-users text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Theses</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $totalTheses ?? '1,243' }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Borrowed Items</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $borrowedBooks ?? '384' }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Returned Items</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $returnedItems ?? '2,156' }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Overdue Items</p>
                            <h3 class="text-2xl font-bold mt-2">{{ $overdueItems ?? '47' }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Management Quick Access -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Quick Access</h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">Management Tools</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="{{ route('authors.index') }}" class="block">
                        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-blue-500">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Authors</h3>
                                    <p class="text-gray-500 text-sm">Manage library authors</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('books.index') }}" class="block">
                        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-green-500">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-book text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Books</h3>
                                    <p class="text-gray-500 text-sm">Manage book collection</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('theses.index') }}" class="block">
                        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-purple-500">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Theses</h3>
                                    <p class="text-gray-500 text-sm">Manage thesis collection</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('users.index') }}" class="block">
                        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-orange-500">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-users text-orange-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Users</h3>
                                    <p class="text-gray-500 text-sm">Manage system users</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('transactions.index') }}" class="block">
                        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-red-500">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-exchange-alt text-red-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Transactions</h3>
                                    <p class="text-gray-500 text-sm">Manage borrow/return</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('userInterface.index') }}" class="block">
                        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-indigo-500">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-search text-indigo-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Library Search</h3>
                                    <p class="text-gray-500 text-sm">Browse resources</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Recent Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Transactions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Recent Transactions</h2>
                        <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-book text-blue-600"></i>
                                </div>
                                <span>John Doe - Cognitive Psychology</span>
                            </div>
                            <span class="text-sm font-medium text-blue-600">Due Today</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-green-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-file-alt text-green-600"></i>
                                </div>
                                <span>Jane Smith - Behavioral Analysis</span>
                            </div>
                            <span class="text-sm font-medium text-green-600">Returned</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-orange-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-book text-orange-600"></i>
                                </div>
                                <span>Robert Brown - Developmental Psych</span>
                            </div>
                            <span class="text-sm font-medium text-orange-600">Overdue</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-file-alt text-blue-600"></i>
                                </div>
                                <span>Emily Davis - Neuropsychology</span>
                            </div>
                            <span class="text-sm font-medium text-blue-600">Due Tomorrow</span>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Quick Actions</h2>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Quick Access</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('books.create') }}" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-plus-circle text-blue-600 text-2xl mb-2"></i>
                            <p class="font-medium text-sm">Add New Book</p>
                        </a>
                        <a href="{{ route('theses.create') }}" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-file-medical text-purple-600 text-2xl mb-2"></i>
                            <p class="font-medium text-sm">Add New Thesis</p>
                        </a>
                        <a href="{{ route('users.create') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-user-plus text-green-600 text-2xl mb-2"></i>
                            <p class="font-medium text-sm">Add New User</p>
                        </a>
                        <a href="{{ route('transactions.create') }}" class="bg-orange-50 hover:bg-orange-100 p-4 rounded-lg text-center transition">
                            <i class="fas fa-exchange-alt text-orange-600 text-2xl mb-2"></i>
                            <p class="font-medium text-sm">New Transaction</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>