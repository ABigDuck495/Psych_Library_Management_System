<!DOCTYPE html>
<html>
<head>
    <title>Edit User - {{ $user->first_name }} {{ $user->last_name }}</title>
</head>
<body>
    <h1>Edit User: {{ $user->first_name }} {{ $user->last_name }}</h1>

    @if($errors->any())
        <div style="background: lightcoral; padding: 10px; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <!-- Common Fields for All Users -->
        <fieldset>
            <legend>Basic Information</legend>
            <div>
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
            </div>

            <div>
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
            </div>

            <div>
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
            </div>

            <div>
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div>
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
            </div>

            <div>
                <label for="password">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password">
            </div>

            <div>
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <div>
                <label for="role">Role *</label>
                <select id="role" name="role" required>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="librarian" {{ old('role', $user->role) == 'librarian' ? 'selected' : '' }}>Librarian</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super-admin" {{ old('role', $user->role) == 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>

            <div>
                <label for="account_status">Account Status *</label>
                <select id="account_status" name="account_status" required>
                    <option value="Active" {{ old('account_status', $user->account_status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('account_status', $user->account_status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </fieldset>

        <!-- User Type Specific Fields -->
        @if($user->isStudent())
            <fieldset>
                <legend>Student Information</legend>
                <div>
                    <label for="academic_program">Academic Program *</label>
                    <select id="academic_program" name="academic_program" required>
                        <option value="Undergraduate" {{ old('academic_program', $user->student->academic_program) == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                        <option value="Masters" {{ old('academic_program', $user->student->academic_program) == 'Masters' ? 'selected' : '' }}>Masters</option>
                        <option value="PhD" {{ old('academic_program', $user->student->academic_program) == 'PhD' ? 'selected' : '' }}>PhD</option>
                    </select>
                </div>

                <div>
                    <label for="major_department">Major/Department *</label>
                    <input type="text" id="major_department" name="major_department" value="{{ old('major_department', $user->student->major_department) }}" required>
                </div>
            </fieldset>
        @else
            <fieldset>
                <legend>Employee Information</legend>
                <div>
                    <label for="department">Department *</label>
                    <input type="text" id="department" name="department" value="{{ old('department', $user->employee->department) }}" required>
                </div>

                <div>
                    <label for="position_title">Position Title *</label>
                    <input type="text" id="position_title" name="position_title" value="{{ old('position_title', $user->employee->position_title) }}" required>
                </div>
            </fieldset>
        @endif

        <div>
            <button type="submit">Update User</button>
            <a href="{{ route('users.show', $user) }}">
                <button type="button">Cancel</button>
            </a>
        </div>
    </form>
</body>
</html>