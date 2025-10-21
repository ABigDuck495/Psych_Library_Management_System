<!DOCTYPE html>
<html>
<head>
    <title>User Details - {{ $user->first_name }} {{ $user->last_name }}</title>
</head>
<body>
    <h1>User Details</h1>

    @if(session('success'))
        <div style="background: lightgreen; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; gap: 30px;">
        <!-- Basic Information -->
        <div style="flex: 1;">
            <h2>Basic Information</h2>
            <p><strong>University ID:</strong> {{ $user->university_id }}</p>
            <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
            <p><strong>Role:</strong> 
                <span style="
                    padding: 3px 8px;
                    border-radius: 3px;
                    background: 
                        @if($user->role === 'super-admin') purple
                        @elseif($user->role === 'admin') red
                        @elseif($user->role === 'librarian') blue
                        @else green @endif;
                    color: white;
                ">
                    {{ ucfirst($user->role) }}
                </span>
            </p>
            <p><strong>Account Status:</strong> 
                <span style="
                    padding: 3px 8px;
                    border-radius: 3px;
                    background: {{ $user->account_status === 'Active' ? 'green' : 'red' }};
                    color: white;
                ">
                    {{ $user->account_status }}
                </span>
            </p>
            <p><strong>Registration Date:</strong> {{ $user->registration_date->format('M d, Y') }}</p>
            <p><strong>Last Login:</strong> {{ $user->last_login_date ? $user->last_login_date->format('M d, Y H:i') : 'Never' }}</p>
        </div>

        <!-- Specific User Type Information -->
        <div style="flex: 1;">
            @if($user->isStudent())
                <h2>Student Information</h2>
                <p><strong>Academic Program:</strong> {{ $user->student->academic_program }}</p>
                <p><strong>Major/Department:</strong> {{ $user->student->major_department }}</p>
            @else
                <h2>Employee Information</h2>
                <p><strong>Department:</strong> {{ $user->employee->department }}</p>
                <p><strong>Position Title:</strong> {{ $user->employee->position_title }}</p>
            @endif
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('users.index') }}">Back to Users</a>
        
        @can('manage-users')
            <a href="{{ route('users.edit', $user) }}">Edit User</a>
            
            @if($user->account_status === 'Active')
                <form action="{{ route('users.deactivate', $user) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" onclick="return confirm('Deactivate this user?')">Deactivate User</button>
                </form>
            @else
                <form action="{{ route('users.activate', $user) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Activate User</button>
                </form>
            @endif

            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Delete this user permanently?')">Delete User</button>
            </form>
        @endcan
    </div>
</body>
</html>