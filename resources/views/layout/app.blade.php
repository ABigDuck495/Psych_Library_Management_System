<!DOCTYPE html>
<html>
<head>
    <title>Psych Library Management System</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <nav>
        <a href="{{ route('home') }}">Home</a> |
        <a href="{{ route('catalogue.index') }}">Catalogue</a> |
        <a href="{{ route('books.index') }}">Books</a> |
        <a href="{{ route('theses.index') }}">Theses</a>
    </nav>

    <hr>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>
