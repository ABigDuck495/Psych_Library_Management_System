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

                <!-- Authors Section (Replaced with Dynamic Author Fields) -->
<div class="border-t border-gray-200 pt-6 mb-6">
    <div class="flex items-center mb-6">
        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
            <i class="fas fa-user-edit text-purple-600"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-800">Author Information</h3>
            <p class="text-gray-600 text-sm">Add authors for this book</p>
        </div>
    </div>

    <!-- Dynamic Author Fields -->
    <div id="authorsContainer" class="space-y-4">
        <!-- First Author by default -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 author-row">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                <input type="text" name="authors[0][first_name]" placeholder="First Name" required
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                <input type="text" name="authors[0][last_name]" placeholder="Last Name" required
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
        </div>
    </div>

    <!-- Add Author Button -->
    <div class="mt-4">
        <button type="button" id="addAuthorBtn"
            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-plus mr-2"></i> Add Author
        </button>
    </div>
</div>

<!-- JavaScript for dynamic authors -->
<script>
    let authorIndex = 1; // start from 1 because first author is 0
    const addAuthorBtn = document.getElementById('addAuthorBtn');
    const authorsContainer = document.getElementById('authorsContainer');

    addAuthorBtn.addEventListener('click', () => {
        const authorRow = document.createElement('div');
        authorRow.classList.add('grid', 'grid-cols-1', 'lg:grid-cols-2', 'gap-4', 'author-row', 'mt-2');

        authorRow.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                <input type="text" name="authors[${authorIndex}][first_name]" placeholder="First Name" required
                    class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                <div class="flex">
                    <input type="text" name="authors[${authorIndex}][last_name]" placeholder="Last Name" required
                        class="form-input block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition">
                    <button type="button" class="ml-2 px-3 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-author-btn transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        authorsContainer.appendChild(authorRow);

        // Add remove functionality
        authorRow.querySelector('.remove-author-btn').addEventListener('click', () => {
            authorRow.remove();
        });

        authorIndex++;
    });
</script>

<!-- Form Actions -->
<div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
    <div>
        <a href="{{ route('theses.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Cancel
        </a>
    </div>
    <div class="flex space-x-3">
        <button 
            type="button" 
            onclick="resetForm()" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
        >
            <i class="fas fa-redo mr-2"></i>
            Reset Form
        </button>
        <button 
            type="submit" 
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
        >
            <i class="fas fa-save mr-2"></i>
            Save Thesis
        </button>
    </div>
</div>

<!-- Quick Tips -->
<div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-lightbulb text-blue-500 text-xl mt-1"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-medium text-blue-800">Quick Tips</h3>
            <div class="mt-2 text-blue-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    <li>Ensure all required fields (marked with *) are filled</li>
                    <li>Add at least one author for the thesis</li>
                    <li>Publication year must be between 1900 and current year</li>
                    <li>Use the "Add Author" button if an author is missing</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Form reset function
    function resetForm() {
        document.getElementById('bookForm').reset();
        showTemporaryMessage('Form has been reset', 'blue');
    }

    // Show temporary message
    function showTemporaryMessage(message, type = 'blue') {
        const existingMessage = document.getElementById('temporaryMessage');
        if (existingMessage) existingMessage.remove();

        const messageDiv = document.createElement('div');
        messageDiv.id = 'temporaryMessage';
        const bgColor = type === 'red' ? 'red' : 'blue';
        messageDiv.className = `fixed top-4 right-4 bg-${bgColor}-100 border border-${bgColor}-400 text-${bgColor}-700 px-4 py-3 rounded-lg shadow-lg z-50`;
        messageDiv.innerHTML = `<div class="flex items-center"><i class="fas fa-info-circle mr-2"></i><span>${message}</span></div>`;

        document.body.appendChild(messageDiv);
        setTimeout(() => { messageDiv.remove(); }, 3000);
    }
</script>
</body>
</html>