<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Psych Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->

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

        /* ===== GREEN SIDEBAR STYLES ===== */
        .sidebar-container {
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #14532d 0%, #16a34a 100%);
            color: white;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .sidebar {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            overflow-y: auto;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 1rem;
        }

        .logout-section {
            margin-top: auto;
            flex-shrink: 0;
            padding-top: 1.5rem;
            border-top: 1px solid #22c55e;
        }

        /* Sidebar Logo */
        .sidebar-logo {
            display: flex;
            align-items: center;
            margin-bottom: 1.3rem;
        }

        .logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: bold;
        }

        /* User Profile Section */
        .user-profile {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        .user-name {
            font-weight: 600;
        }

        .user-role {
            color: #bbf7d0;
            font-size: 0.875rem;
            text-transform: capitalize;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .user-detail {
            display: flex;
            justify-content: space-between;
        }

        .detail-label {
            color: #bbf7d0;
        }

        .detail-value {
            color: white;
        }

        .status-active {
            color: #86efac;
        }

        /* Navigation Sections */
        .nav-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #bbf7d0;
            margin-bottom: 1rem;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 0.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background-color: rgba(21, 128, 61, 0.7);
        }

        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid white;
        }

        .nav-icon {
            margin-right: 0.75rem;
            width: 1rem;
            text-align: center;
        }

        /* Logout Button */
        .logout-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            background: none;
            border: none;
            cursor: pointer;
            font-size: inherit;
        }

        .logout-btn:hover {
            background-color: rgba(21, 128, 61, 0.7);
        }

        /* Main content adjustment */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
            padding: 1rem;
            width: calc(100% - 280px);
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .sidebar-container {
                width: 100%;
                height: 100vh;
                position: fixed;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
                box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            }

            .sidebar-container.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }

            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background: white;
                border: none;
                border-radius: 0.5rem;
                padding: 0.75rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                cursor: pointer;
                color: #14532d;
            }
            
            .mobile-menu-btn.hidden {
                display: none;
            }
        }

        /* Scrollbar styling for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Clickable user profile styles */
        .user-profile-link {
            display: block;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-profile-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .user-profile-link:hover .user-avatar {
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
    </style>
    
    @stack('styles')
</head>
<body class="dashboard-bg min-h-screen">
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn hidden md:hidden" id="mobileMenuBtn">
        <i class="fas fa-bars text-lg"></i>
    </button>
    
    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="flex">
        <!-- Sidebar Navigation -->
        <div class="sidebar-container" id="sidebarContainer">
            <div class="sidebar">
                <!-- Logo and App Name -->
                <div class="sidebar-logo">
                    <div class="logo-icon">
                        <i class="fas fa-book text-green-800 text-lg"></i>
                    </div>
                    <h1 class="logo-text">Psych Library</h1>
                </div>
                
                <!-- User Profile Section - Now Clickable -->
                <a href="{{ route('users.editSelf') }}" class="user-profile-link">
                    <div class="user-profile">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <div class="user-name">{{ Auth::user()->username }}</div>
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
                </a>
                
                <!-- Scrollable Content -->
                <div class="sidebar-content">
                    <!-- Navigation Menu -->
                    <div class="nav-section">
                        <h2 class="section-title">Main Navigation</h2>
                        <ul class="nav-menu">
                            <li>
                                <a href="{{ route('home') }}" 
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

                    <!-- User Sections - Only for Regular Users -->
                    @if(!in_array(Auth::user()->role, ['admin', 'librarian']))
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
                    
                    <!-- Library Resources - Only for Regular Users -->
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
                    @endif
                </div>

                <!-- Logout Button - Always visible at bottom -->
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
        </div>

        <!-- Main Content Area -->
        <main class="main-content flex-1 overflow-auto">
            @yield('content')
        </main>
    </div>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebarContainer = document.getElementById('sidebarContainer');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (mobileMenuBtn && sidebarContainer) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebarContainer.classList.toggle('mobile-open');
                    sidebarOverlay.classList.toggle('active');
                });
            }
            
            // Close sidebar when clicking overlay on mobile
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebarContainer.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    const isClickInsideSidebar = sidebarContainer.contains(event.target);
                    const isClickOnMenuBtn = mobileMenuBtn.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnMenuBtn && sidebarContainer.classList.contains('mobile-open')) {
                        sidebarContainer.classList.remove('mobile-open');
                        sidebarOverlay.classList.remove('active');
                    }
                }
            });
            
            // Adjust mobile menu button visibility
            function adjustMobileMenu() {
                if (window.innerWidth <= 768) {
                    mobileMenuBtn.classList.remove('hidden');
                } else {
                    mobileMenuBtn.classList.add('hidden');
                    sidebarContainer.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                }
            }
            
            // Initial adjustment
            adjustMobileMenu();
            
            // Adjust on resize
            window.addEventListener('resize', adjustMobileMenu);
        });
    </script>

    @stack('scripts')
</body>
</html>