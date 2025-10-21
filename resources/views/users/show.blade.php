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
 
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('users.index') }}">Back to Users</a>
        
        
        
    </div>
</body>
</html>