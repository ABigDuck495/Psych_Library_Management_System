<!DOCTYPE html>
<html>
<head>
    <title>Create New User</title>
</head>
<body>
    <h1>Create New User</h1>

    @if($errors->any())
        <div style="background: lightcoral; padding: 10px; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <!-- Common Fields for All Users -->
        <fieldset>
            <legend>Basic Information</legend>
            <div>
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
            </div>

            <div>
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
            </div>

            <div>
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required>
            </div>

            <div>
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div>
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
            </div>

            <div>
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="password_confirmation">Confirm Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div>
                <label for="role">Role *</label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="librarian" {{ old('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super-admin" {{ old('role') == 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>

            <div>
                <label for="account_status">Account Status *</label>
                <select id="account_status" name="account_status" required>
                    <option value="Active" {{ old('account_status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('account_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </fieldset>

        <!-- User Type Selection -->
        <fieldset>
            <legend>Account Type</legend>
            <div>
                <label>
                    <input type="radio" name="user_type" value="student" {{ old('user_type') == 'student' ? 'checked' : '' }} required>
                    Student
                </label>
                <label>
                    <input type="radio" name="user_type" value="employee" {{ old('user_type') == 'employee' ? 'checked' : '' }}>
                    Employee
                </label>
            </div>
        </fieldset>

        <!-- Student Specific Fields -->
        <fieldset id="student_fields" style="display: none;">
            <legend>Student Information</legend>
            <div>
                <label for="academic_program">Academic Program *</label>
                <select id="academic_program" name="academic_program">
                    <option value="">Select Program</option>
                    <option value="Undergraduate" {{ old('academic_program') == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                    <option value="Masters" {{ old('academic_program') == 'Masters' ? 'selected' : '' }}>Masters</option>
                    <option value="PhD" {{ old('academic_program') == 'PhD' ? 'selected' : '' }}>PhD</option>
                </select>
            </div>

            <div>
                <label for="major_department">Major/Department *</label>
                <input type="text" id="major_department" name="major_department" value="{{ old('major_department') }}">
            </div>
        </fieldset>

        <!-- Employee Specific Fields -->
        <fieldset id="employee_fields" style="display: none;">
            <legend>Employee Information</legend>
            <div>
                <label for="department">Department *</label>
                <input type="text" id="department" name="department" value="{{ old('department') }}">
            </div>

            <div>
                <label for="position_title">Position Title *</label>
                <input type="text" id="position_title" name="position_title" value="{{ old('position_title') }}">
            </div>
        </fieldset>

        <div>
            <button type="submit">Create User</button>
            <a href="{{ route('users.index') }}">
                <button type="button">Cancel</button>
            </a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentFields = document.getElementById('student_fields');
            const employeeFields = document.getElementById('employee_fields');
            const userTypeRadios = document.querySelectorAll('input[name="user_type"]');

            function toggleFields() {
                const selectedType = document.querySelector('input[name="user_type"]:checked')?.value;
                
                studentFields.style.display = selectedType === 'student' ? 'block' : 'none';
                employeeFields.style.display = selectedType === 'employee' ? 'block' : 'none';

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

            userTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleFields);
            });

            // Initialize on page load
            toggleFields();
        });
    </script>
</body>
</html>