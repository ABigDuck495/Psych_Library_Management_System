<!DOCTYPE html>
<html>
<head>
    <title>Register - Library System</title>
</head>
<body>
    <h1>Register for Library System</h1>

    @if($errors->any())
        <div style="background: lightcoral; padding: 10px; margin: 10px 0;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="university_id">University ID</label>
            <input type="text" id="university_id" name="university_id" value="{{ old('university_id') }}" required>
        </div>
        <div>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
        </div>

        <div>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
        </div>

        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required>
        </div>

        <div>
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="user">user</option>
                <option value="librarian">librarian</option>
                <option value="admin">admin</option>
            </select>
        </div>
        <div>
            <label for="phone_number">Phone Number: </label>
            <input type="number" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div>
            <button type="submit">Register</button>
        </div>
    </form>

    <div>
        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</body>
</html>