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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Theses Management</h1>
                <p class="text-gray-600 mt-2">Manage and organize academic theses in the library system</p>
            </div>
            
            <div>
                <a href="{{ route('theses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
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
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($theses as $thesis)
                            <tr class="table-row-hover">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $thesis->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs">{{ $thesis->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-md">
                                    <div class="abstract-preview">{{ $thesis->abstract }}</div>
                                    @if(strlen($thesis->abstract) > 200)
                                        <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                                onclick="toggleAbstract({{ $thesis->id }})">
                                            Show more
                                        </button>
                                        <div id="full-abstract-{{ $thesis->id }}" class="hidden mt-2">
                                            {{ $thesis->abstract }}
                                            <button class="text-blue-600 text-xs mt-1 hover:text-blue-800 focus:outline-none" 
                                                    onclick="toggleAbstract({{ $thesis->id }})">
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
                                        <a href="{{ route('theses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition">
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            Add New Thesis
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Simple pagination info (if you want to add pagination later) -->
        @if($theses->count() > 0)
            <div class="mt-4 text-sm text-gray-600">
                Showing {{ $theses->count() }} thesis/theses
            </div>
        @endif
    </div>

    <!-- JavaScript for abstract toggle -->
    <script>
        function toggleAbstract(thesisId) {
            const preview = document.querySelector(`tr:has(td #full-abstract-${thesisId}) .abstract-preview`);
            const fullAbstract = document.getElementById(`full-abstract-${thesisId}`);
            
            if (preview && fullAbstract) {
                if (fullAbstract.classList.contains('hidden')) {
                    preview.style.display = 'none';
                    fullAbstract.classList.remove('hidden');
                } else {
                    preview.style.display = '-webkit-box';
                    fullAbstract.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html>