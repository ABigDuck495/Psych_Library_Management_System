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
    <!-- Button linking to catalogue -->
     <a href="{{ route('catalogue') }}">
        <button type="button">Go to Catalogue</button>
    </a>

    <h3>student/staff view</h3>
</a>
</body>
</html>
