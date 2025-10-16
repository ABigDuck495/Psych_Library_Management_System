<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Author</title>
</head>
<body>
    <h1>Add New Author</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('authors.store') }}" method="POST">
        @csrf
        //for uhhh when you add an author while adding a book or thesis
        <input type="hidden" name="return_to" value="{{ request('return_to', url()->previous()) }}">

        <label>First Name:</label>
        <input type="text" name="first_name" value="{{ old('first_name') }}"><br><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="{{ old('last_name') }}"><br><br>

        <button type="submit">Save</button>
        <a href="{{ route('authors.index') }}">Cancel</a>
    </form>
</body>
</html>
