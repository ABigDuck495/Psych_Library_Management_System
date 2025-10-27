<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Psych Library Management System</title>
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
    
    @stack('styles')
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
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="user-role">{{ Auth::user()->role }}</div>
                    </div>
                </div>
                <div class="user-details">
                    <div class="user-detail">
                        <span class="detail-label">University ID:</span>
                        <span class="detail-value">{{ Auth::user()->university_id }}</span>
                    </div>
                    <div class="user-detail">
                        <span class="detail-label">Status:</span>
                        <span class="status-active">{{ Auth::user()->account_status }}</span>
                    </div>
                </div>
            </div>
             
            <!-- Navigation Menu -->
            <div class="nav-section">
                <h2 class="section-title">Main Navigation</h2>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ in_array(Auth::user()->role, ['admin', 'librarian']) ? route('adminInterface.index') : route('userInterface.index') }}" 
                           class="nav-item {{ in_array(Auth::user()->role, ['admin', 'librarian']) ? 
                                            (request()->routeIs('adminInterface.*') ? 'active' : '') : 
                                            (request()->routeIs('userInterface.*') ? 'active' : '') }}">
                            <i class="fas fa-home nav-icon"></i>
                            Dashboard
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Management Sections - Only for Admin and Librarian -->
            @if(in_array(Auth::user()->role, ['admin', 'librarian']))
            <div class="nav-section">
                <h2 class="section-title">Management</h2>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            User Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}" class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
                            <i class="fas fa-book-open nav-icon"></i>
                            Book Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('theses.index') }}" class="nav-item {{ request()->routeIs('theses.*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            Thesis Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('authors.index') }}" class="nav-item {{ request()->routeIs('authors.*') ? 'active' : '' }}">
                            <i class="fas fa-user-edit nav-icon"></i>
                            Author Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt nav-icon"></i>
                            Transactions
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            <!-- User Sections - For All Users -->
            <div class="nav-section">
                <h2 class="section-title">My Account</h2>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ route('userInterface.borrowedBooks') }}" class="nav-item {{ request()->routeIs('userInterface.borrowedBooks') ? 'active' : '' }}">
                            <i class="fas fa-book nav-icon"></i>
                            My Borrowed Items
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('userInterface.borrowingHistory') }}" class="nav-item {{ request()->routeIs('userInterface.borrowingHistory') ? 'active' : '' }}">
                            <i class="fas fa-history nav-icon"></i>
                            Borrowing History
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Library Resources - For All Users -->
            <div class="nav-section">
                <h2 class="section-title">Library Resources</h2>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ route('theses.index') }}" class="nav-item">
                            <i class="fas fa-file-alt nav-icon"></i>
                            Academic Theses
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}" class="nav-item">
                            <i class="fas fa-book-open nav-icon"></i>
                            Book Collection
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

        <!-- Main Content Area -->
        <main class="flex-1 overflow-auto">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>