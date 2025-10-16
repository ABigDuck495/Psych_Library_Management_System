<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>
</head>
<body>
    <h1>Catalogue</h1>

    <a href="{{ route('books.addInventory') }}">
        <button type="button">Add Inventory</button>
    </a>

     <a href="{{ route('book_authors.index') }}">
        <button type="button">Manage Book Authors</button>
    </a>

    <a href="{{ route('theses.index') }}">
        <button type="button">Manage Theses</button>
    </a>
</body>
</html>
