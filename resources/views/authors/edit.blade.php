<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Author</title>
</head>
<body>
    <h1>Edit Author</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('authors.update', $author->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>First Name:</label>
        <input type="text" name="first_name" value="{{ old('first_name', $author->first_name) }}"><br><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="{{ old('last_name', $author->last_name) }}"><br><br>

        <button type="submit">Update</button>
        <a href="{{ route('authors.index') }}">Cancel</a>
    </form>
</body>
</html>
