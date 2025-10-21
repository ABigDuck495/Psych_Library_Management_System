<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Thesis - Psych Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .form-input:focus {
            outline: none;
            /* emulate Tailwind's ring using box-shadow */
            box-shadow: 0 0 0 2px rgba(59,130,246,0.25);
            border-color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Add New Thesis</h1>
            <p class="text-gray-600 mt-2">Create a new thesis entry in the library system</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Thesis Form -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form action="{{ route('theses.store') }}" method="POST">
                @csrf

                <!-- Title Field -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Enter thesis title" required>
                    @error('title')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Abstract Field -->
                <div class="mb-6">
                    <label for="abstract" class="block text-sm font-medium text-gray-700 mb-2">Abstract *</label>
                    <textarea id="abstract" name="abstract" rows="5" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                              placeholder="Enter thesis abstract" required>{{ old('abstract') }}</textarea>
                    @error('abstract')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Year Published and Department Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Year Published -->
                    <div>
                        <label for="year_published" class="block text-sm font-medium text-gray-700 mb-2">Year Published *</label>
                        <input type="number" id="year_published" name="year_published" 
                               value="{{ old('year_published') }}" min="1900" max="{{ date('Y') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               required>
                        @error('year_published')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                        <select id="department" name="department" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                required>
                            <option value="">Select Department</option>
                            <option value="AB Psychology" {{ old('department') == 'AB Psychology' ? 'selected' : '' }}>AB Psychology</option>
                            <option value="BS Psychology" {{ old('department') == 'BS Psychology' ? 'selected' : '' }}>BS Psychology</option>
                        </select>
                        @error('department')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Copies Count -->
                <div class="mb-8">
                    <label for="copies_count" class="block text-sm font-medium text-gray-700 mb-2">Number of Copies *</label>
                    <input type="number" id="copies_count" name="copies_count" 
                           value="{{ old('copies_count', 1) }}" min="1" max="20" 
                           class="w-32 px-4 py-2 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                    @error('copies_count')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Authors Section -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users mr-2 text-blue-600"></i>
                        Author Information
                    </h3>

                    <div class="mb-4">
                        <label for="author_ids" class="block text-sm font-medium text-gray-700 mb-2">Authors *</label>
                        <div class="space-y-4">
                            <select id="author_ids" name="author_ids[]" multiple 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    style="height: 150px;" required>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}"
                                        {{ in_array($author->id, old('author_ids', [])) ? 'selected' : '' }}>
                                        {{ $author->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Hold Ctrl (or Cmd on Mac) to select multiple authors
                                </div>
                                
                                <a href="{{ route('authors.create', ['return_to' => url()->current()]) }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition text-sm">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    Add New Author
                                </a>
                            </div>
                        </div>
                        @error('author_ids')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('theses.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium flex items-center transition">
                        <i class="fas fa-save mr-2"></i>
                        Save Thesis
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Tips -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                <i class="fas fa-lightbulb mr-2"></i>
                Quick Tips
            </h4>
            <ul class="text-blue-700 text-sm space-y-1">
                <li>• Ensure all required fields (marked with *) are filled</li>
                <li>• Select multiple authors by holding Ctrl/Cmd while clicking</li>
                <li>• Add new authors using the "Add New Author" button if needed</li>
                <li>• Year published should be between 1900 and current year</li>
            </ul>
        </div>
    </div>

    <script>
        // Auto-focus on title field
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            if (titleInput) {
                titleInput.focus();
            }

            // Character counter for abstract
            const abstractTextarea = document.getElementById('abstract');
            if (abstractTextarea) {
                // Create character counter element
                const counter = document.createElement('div');
                counter.className = 'text-sm text-gray-500 mt-1 text-right';
                abstractTextarea.parentNode.appendChild(counter);

                function updateCounter() {
                    const length = abstractTextarea.value.length;
                    counter.textContent = `${length} characters`;
                    
                    if (length > 1000) {
                        counter.className = 'text-sm text-red-500 mt-1 text-right';
                    } else if (length > 500) {
                        counter.className = 'text-sm text-yellow-500 mt-1 text-right';
                    } else {
                        counter.className = 'text-sm text-gray-500 mt-1 text-right';
                    }
                }

                abstractTextarea.addEventListener('input', updateCounter);
                updateCounter(); // Initial count
            }
        });
    </script>
</body>
</html>