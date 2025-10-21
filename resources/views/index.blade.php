<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to Psych Library Management System</h1>

    <!-- Button linking to authors index -->
     <h3>admin/superadmin view</h3>
    <a href="{{ route('authors.index') }}">
        <button type="button">Go to Authors</button>
    </a>
    <a href="{{ route('books.index') }}">
        <button type="button">Go to Books</button>
    </a>

    <a href="{{ route('theses.index') }}">
        <button type="button">Go to Theses</button>
    </a>

    <a href="{{ route('userInterface.index') }}">
        <button type="button">User Interface</button>
    </a>

    <a href="{{ route('adminInterface.index') }}">
        <button type="button">Admin Interface</button>
    </a>
    
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <h3>student/staff view</h3>
</a>
</body>
</html>
