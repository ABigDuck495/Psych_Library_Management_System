@extends('layouts.app')

@section('title', 'Create New User - Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
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
                        <label for="position_title" class="block text-sm font-medium text-gray-700 mb-2">Position/Title *</label>
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

@push('styles')
<style>
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
@endpush

@push('scripts')
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
@endpush
@endsection