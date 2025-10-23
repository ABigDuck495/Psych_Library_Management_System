<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Psych Library Management System</title>
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
                        <a href="{{ route('index') }}" class="flex items-center p-3 rounded-lg active-nav">
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
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Welcome to Psych Library Management System</h1>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Total Books</p>
                            <h3 class="text-2xl font-bold mt-2">1,248</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-green-500 text-sm mt-4"><i class="fas fa-arrow-up mr-1"></i> 12% from last month</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Active Users</p>
                            <h3 class="text-2xl font-bold mt-2">586</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-green-500 text-sm mt-4"><i class="fas fa-arrow-up mr-1"></i> 8% from last month</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Borrowed Items</p>
                            <h3 class="text-2xl font-bold mt-2">142</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-red-500 text-sm mt-4"><i class="fas fa-arrow-down mr-1"></i> 3% from last month</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Theses</p>
                            <h3 class="text-2xl font-bold mt-2">324</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-green-500 text-sm mt-4"><i class="fas fa-arrow-up mr-1"></i> 5% from last month</p>
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
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-book text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm">Introduction to Psychology</p>
                                    <p class="text-gray-500 text-xs">Borrowed by John Doe</p>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Due Today</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-file-alt text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm">Cognitive Behavioral Therapy</p>
                                    <p class="text-gray-500 text-xs">Returned by Jane Smith</p>
                                </div>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Returned</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-clock text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-sm">Research Methods</p>
                                    <p class="text-gray-500 text-xs">Requested by Mike Johnson</p>
                                </div>
                            </div>
                            <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Pending</span>
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

