<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User - Psych Library</title>
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
        
        .form-section {
            transition: all 0.3s ease;
        }
        
        .radio-card {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }
        
        .radio-card:hover {
            border-color: #3b82f6;
        }
        
        .radio-card.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
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
                            <i class="fas fa-home mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('userInterface.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-book mr-3"></i>
                            Library Resources
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-history mr-3"></i>
                            Borrowing History
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
                    <h1 class="text-3xl font-bold text-gray-800">Create New User</h1>
                    <p class="text-gray-600 mt-2">Add a new user to the library management system</p>
                </div>
                
                <div>
                    <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Users
                    </a>
                </div>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Please fix the following errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- User Creation Form -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <form method="POST" action="{{ route('users.store') }}" class="space-y-8">
                    @csrf

                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                            Basic Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="university_id" class="block text-sm font-medium text-gray-700 mb-2">University ID *</label>
                                <input type="text" id="university_id" name="university_id" value="{{ old('university_id') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                                <input type="password"
                                            name="password"
                                            required
                                            minlength="8"
                                            pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}"
                                            title="Password must be at least 8 characters and include uppercase, lowercase, number, and special character."
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                                <select id="role" name="role" 
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Select Role</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="librarian" {{ old('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="super-admin" {{ old('role') == 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                            </div>

                            <div>
                                <label for="account_status" class="block text-sm font-medium text-gray-700 mb-2">Account Status *</label>
                                <select id="account_status" name="account_status" 
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="Active" {{ old('account_status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('account_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Account Type Selection -->
                    <div class="form-section">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-id-card mr-2 text-green-600"></i>
                            Account Type
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="user_type" value="student" {{ old('user_type') == 'student' ? 'checked' : '' }} 
                                       class="hidden" required>
                                <div class="p-4 rounded-lg border-2 text-center">
                                    <i class="fas fa-graduation-cap text-3xl text-blue-600 mb-2"></i>
                                    <h3 class="font-semibold text-gray-800">Student</h3>
                                    <p class="text-sm text-gray-600 mt-1">Academic program user</p>
                                </div>
                            </label>

                            <label class="radio-card cursor-pointer">
                                <input type="radio" name="user_type" value="employee" {{ old('user_type') == 'employee' ? 'checked' : '' }} 
                                       class="hidden">
                                <div class="p-4 rounded-lg border-2 text-center">
                                    <i class="fas fa-briefcase text-3xl text-green-600 mb-2"></i>
                                    <h3 class="font-semibold text-gray-800">Employee</h3>
                                    <p class="text-sm text-gray-600 mt-1">Staff or faculty member</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Student Specific Fields -->
                    <div id="student_fields" class="form-section hidden">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-university mr-2 text-blue-600"></i>
                            Student Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="academic_program" class="block text-sm font-medium text-gray-700 mb-2">Academic Program *</label>
                                <select id="academic_program" name="academic_program" 
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Program</option>
                                    <option value="Undergraduate" {{ old('academic_program') == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                    <option value="Masters" {{ old('academic_program') == 'Masters' ? 'selected' : '' }}>Masters</option>
                                    <option value="PhD" {{ old('academic_program') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                </select>
                            </div>

                            <div>
                                <label for="major_department" class="block text-sm font-medium text-gray-700 mb-2">Major/Department *</label>
                                <input type="text" id="major_department" name="major_department" value="{{ old('major_department') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Employee Specific Fields -->
                    <div id="employee_fields" class="form-section hidden">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-briefcase mr-2 text-green-600"></i>
                            Employee Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                                <input type="text" id="department" name="department" value="{{ old('department') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="position_title" class="block text-sm font-medium text-gray-700 mb-2">positionuse/Title *</label>
                                <input type="text" id="position_title" name="position_title" value="{{ old('position_title') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium flex items-center transition">
                            <i class="fas fa-user-plus mr-2"></i>
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentFields = document.getElementById('student_fields');
            const employeeFields = document.getElementById('employee_fields');
            const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
            const radioCards = document.querySelectorAll('.radio-card');

            function toggleFields() {
                const selectedType = document.querySelector('input[name="user_type"]:checked')?.value;
                
                // Toggle visibility
                studentFields.classList.toggle('hidden', selectedType !== 'student');
                employeeFields.classList.toggle('hidden', selectedType !== 'employee');

                // Update radio card styles
                radioCards.forEach(card => {
                    const radio = card.querySelector('input[type="radio"]');
                    if (radio.checked) {
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                });

                // Make fields required/optional based on selection
                const studentRequired = selectedType === 'student';
                const employeeRequired = selectedType === 'employee';

                document.querySelectorAll('#student_fields input, #student_fields select').forEach(field => {
                    field.required = studentRequired;
                });

                document.querySelectorAll('#employee_fields input, #employee_fields select').forEach(field => {
                    field.required = employeeRequired;
                });
            }

            // Add click event to radio cards
            radioCards.forEach(card => {
                card.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    toggleFields();
                });
            });

            // Initialize on page load
            toggleFields();

            // Add real-time validation feedback
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.required && !this.value) {
                        this.classList.add('border-red-500');
                    } else {
                        this.classList.remove('border-red-500');
                    }
                });
            });
        });
    </script>
</body>
</html>