<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Psych Library Management System</title>
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
        
        .active-link {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid white;
        }
    </style>
</head>
<body class="dashboard-bg min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 min-h-screen p-6 text-white">
            <div class="flex items-center mb-10">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center mr-3">
                    <i class="fas fa-book text-blue-800 text-lg"></i>
                </div>
                <h1 class="text-xl font-bold">Psych Library</h1>
            </div>
            
            <div class="mb-8">
                <h2 class="text-sm uppercase tracking-wider text-blue-200 mb-4">Main Navigation</h2>
                <ul class="space-y-2">
                    <li><a href="#" class="flex items-center p-3 rounded-lg active-link">
                        <i class="fas fa-home mr-3"></i>
                        Dashboard
                    </a></li>
                    <li><a href="{{ route('books.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-book mr-3"></i>
                        Books
                    </a></li>
                    <li><a href="{{ route('authors.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-user-edit mr-3"></i>
                        Authors
                    </a></li>
                    <li><a href="{{ route('theses.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-file-alt mr-3"></i>
                        Theses
                    </a></li>
                    <li><a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-users mr-3"></i>
                        Users
                    </a></li>
                    <li><a href="{{ route('transactions.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-exchange-alt mr-3"></i>
                        Transactions
                    </a></li>
                </ul>
            </div>
            
            <div class="mb-8">
                <h2 class="text-sm uppercase tracking-wider text-blue-200 mb-4">User Views</h2>
                <ul class="space-y-2">
                    <li><a href="{{ route('userInterface.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-user mr-3"></i>
                        User Interface
                    </a></li>
                </ul>
            </div>
            
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
                    <p class="text-gray-600 mt-2">Manage your library resources efficiently</p>
                </div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">Admin User</p>
                        <p class="text-sm text-gray-500">Administrator</p>
                    </div>
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
            
            <!-- Admin/Superadmin Section -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Admin/Superadmin View</h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">Full Access</span>
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
                </div>
            </div>
            
            <!-- Student/Staff Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Student/Staff View</h2>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Limited Access</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('userInterface.index') }}" class="block">
                        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-indigo-500">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-indigo-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">User Interface</h3>
                                    <p class="text-gray-500 text-sm">Access library resources</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm p-6 border border-blue-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center mr-4 shadow-sm">
                                <i class="fas fa-search text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">Quick Search</h3>
                                <p class="text-gray-500 text-sm">Find books, theses, and more</p>
                            </div>
                        </div>
                        <div class="mt-4 flex">
                            <input type="text" placeholder="Search the library..." class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button class="bg-blue-600 text-white px-4 rounded-r-lg hover:bg-blue-700 transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>