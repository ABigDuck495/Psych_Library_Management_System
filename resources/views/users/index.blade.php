<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
</head>
<body>
    <h1>User Management</h1>

    @can('manage-users')
        <div>
            <a href="{{ route('users.create') }}">
                <button>Add New User</button>
            </a>
        </div>
    @endcan

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('users.index') }}" style="margin: 20px 0; padding: 10px; border: 1px solid #ccc;">
        <div>
            <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}">
            <select name="role">
                <option value="">All Roles</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="librarian" {{ request('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="super-admin" {{ request('role') == 'super-admin' ? 'selected' : '' }}>Super Admin</option>
            </select>
            <select name="user_type">
                <option value="">All Types</option>
                <option value="student" {{ request('user_type') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="employee" {{ request('user_type') == 'employee' ? 'selected' : '' }}>Employee</option>
            </select>
            <select name="account_status">
                <option value="">All Status</option>
                <option value="Active" {{ request('account_status') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ request('account_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit">Filter</button>
            <a href="{{ route('users.index') }}">Clear</a>
        </div>
    </form>

    @if(session('success'))
        <div style="background: lightgreen; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: lightcoral; padding: 10px; margin: 10px 0;">
            {{ session('error') }}
        </div>
    @endif

    @if($users->count() > 0)
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>University ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->university_id }}</td>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span style="
                            padding: 3px 8px;
                            border-radius: 3px;
                            font-size: 12px;
                            background: 
                                @if($user->role === 'super-admin') purple
                                @elseif($user->role === 'admin') red
                                @elseif($user->role === 'librarian') blue
                                @else green @endif;
                            color: white;
                        ">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($user->user_type) }}</td>
                    <td>
                        <span style="
                            padding: 3px 8px;
                            border-radius: 3px;
                            font-size: 12px;
                            background: {{ $user->account_status === 'Active' ? 'green' : 'red' }};
                            color: white;
                        ">
                            {{ $user->account_status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('users.show', $user) }}">View</a>
                        
                        @can('manage-users')
                            <a href="{{ route('users.edit', $user) }}">Edit</a>
                            
                            @if($user->account_status === 'Active')
                                <form action="{{ route('users.deactivate', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('Deactivate this user?')">Deactivate</button>
                                </form>
                            @else
                                <form action="{{ route('users.activate', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">Activate</button>
                                </form>
                            @endif

                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this user permanently?')">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    @else
        <p>No users found.</p>
    @endif
</body>
</html>