<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theses Management - Psych Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .abstract-preview {
            display: -webkit-box;
            /* standard property for compatibility */
            line-clamp: 3;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
        
        .department-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .dashboard-btn {
            transition: all 0.3s ease;
        }
        
        .dashboard-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .add-thesis-btn {
            transition: all 0.3s ease;
        }
        
        .add-thesis-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
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
                       <a href="{{ route('home') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-users mr-3"></i>
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
                        <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
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
                        <a href="{{ route('theses.index') }}" class="flex items-center p-3 rounded-lg active-nav">
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
                    <h1 class="text-3xl font-bold text-gray-800">Theses Management</h1>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('theses.create') }}" class="add-thesis-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Add New Thesis
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search Theses</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchInput" placeholder="Title, abstract, or author..." 
                                   class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <!-- Department Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select id="departmentFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Departments</option>
                            <option value="AB Psychology">AB Psychology</option>
                            <option value="BS Psychology">BS Psychology</option>
                        </select>
                    </div>
                    
                    <!-- Year Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Year Published</label>
                        <select id="yearFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Years</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
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

            <!-- Theses Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Abstract</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Published</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Authors</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="thesesTableBody">
                            @forelse ($theses as $thesis)
                                <tr class="table-row-hover thesis-row" 
                                    data-title="{{ strtolower($thesis->title) }}"
                                    data-department="{{ strtolower($thesis->department) }}"
                                    data-year="{{ $thesis->year_published }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $thesis->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs">{{ $thesis->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-md">
                                        <div class="abstract-preview">{{ $thesis->abstract }}</div>
                                        @if(strlen($thesis->abstract) > 200)
                                            <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                                    onclick="toggleAbstract('{{ $thesis->id }}')">
                                                Show more
                                            </button>
                                            <div id="full-abstract-{{ $thesis->id }}" class="hidden mt-2 text-sm text-gray-600">
                                                {{ $thesis->abstract }}
                                                <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                                        onclick="toggleAbstract('{{ $thesis->id }}')">
                                                    Show less
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-blue-100 text-blue-800 department-badge">
                                            {{ $thesis->year_published }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="bg-purple-100 text-purple-800 department-badge">
                                            {{ $thesis->department }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($thesis->authors->isNotEmpty())
                                            <div class="space-y-1">
                                                @foreach ($thesis->authors as $author)
                                                    <div class="flex items-center">
                                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                        <span>{{ $author->first_name }} {{ $author->last_name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">No authors linked</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('theses.edit', $thesis->id) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 action-btn" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <a href="{{ route('theses.show', $thesis->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 action-btn" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <form action="{{ route('theses.destroy', $thesis->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this thesis?');"
                                                        class="text-red-600 hover:text-red-900 action-btn" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-file-alt text-5xl mb-4"></i>
                                            <h3 class="text-lg font-medium mb-2">No theses found</h3>
                                            <p class="mb-4">Get started by adding your first thesis</p>
                                            <div class="flex space-x-3">
                                                <a href="{{ route('theses.create') }}" class="add-thesis-btn bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2 rounded-lg font-medium flex items-center transition shadow-md">
                                                    <i class="fas fa-plus-circle mr-2"></i>
                                                    Add New Thesis
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

            <!-- Pagination -->
            @if($theses->count() > 0)
                <div class="mt-6">
                    {{ $theses->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for abstract toggle and filtering -->
    <script>
        // Toggle abstract visibility
        function toggleAbstract(thesisId) {
            const element = document.getElementById(`full-abstract-${thesisId}`);
            if (element) {
                element.classList.toggle('hidden');
                const button = element.previousElementSibling;
                if (button && button.tagName === 'BUTTON') {
                    button.textContent = element.classList.contains('hidden') ? 'Show more' : 'Show less';
                }
            }
        }

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const departmentFilter = document.getElementById('departmentFilter');
            const yearFilter = document.getElementById('yearFilter');
            const filterButton = document.getElementById('filterButton');
            const resetButton = document.getElementById('resetButton');
            const thesisRows = document.querySelectorAll('.thesis-row');

            function filterTheses() {
                const searchTerm = searchInput.value.toLowerCase();
                const departmentValue = departmentFilter.value.toLowerCase();
                const yearValue = yearFilter.value;

                thesisRows.forEach(row => {
                    const title = row.getAttribute('data-title');
                    const department = row.getAttribute('data-department');
                    const year = row.getAttribute('data-year');

                    const matchesSearch = title.includes(searchTerm);
                    const matchesDepartment = !departmentValue || department.includes(departmentValue);
                    const matchesYear = !yearValue || year === yearValue;

                    row.style.display = (matchesSearch && matchesDepartment && matchesYear) ? '' : 'none';
                });
            }

            filterButton.addEventListener('click', filterTheses);
            
            resetButton.addEventListener('click', function() {
                searchInput.value = '';
                departmentFilter.value = '';
                yearFilter.value = '';
                thesisRows.forEach(row => row.style.display = '');
            });

            // Real-time search
            searchInput.addEventListener('input', filterTheses);
        });
    </script>

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