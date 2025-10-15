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
    <a href="{{ route('authors.index') }}">
        <button type="button">Go to Authors</button>
    </a>
    <!-- Button linking to catalogue -->
     <a href="{{ route('catalogue') }}">
        <button type="button">Go to Catalogue</button>
    </a>
</a>
</body>
</html>
