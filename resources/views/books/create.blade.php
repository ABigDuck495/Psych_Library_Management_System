<form action="{{ route('books.store') }}" method="POST">
    @csrf

    <label for="title">Title:</label>
    <input type="text" name="title" required>

    <label for="description">Description:</label>
    <textarea name="description" required></textarea>

    <label for="year_published">Year Published:</label>
    <input type="number" name="year_published" min="1900" max="{{ date('Y') }}" required>

    <label for="category_id">Category:</label>
    <select name="category_id" required>
        <option value="">-- Select Category --</option>
        @foreach ($categories as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>

    <h3>Author Information</h3>
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" required>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" required>

    <button type="submit">Save Book</button>
</form>
