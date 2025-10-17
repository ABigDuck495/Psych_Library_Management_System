<form action="{{ route('theses.store') }}" method="POST">
    @csrf

    <label for="title">Title:</label>
    <input type="text" name="title" required>

    <label for="abstract">Abstract:</label>
    <textarea name="abstract" required></textarea>

    <label for="year_published">Year Published:</label>
    <input type="number" name="year_published" min="1900" max="{{ date('Y') }}" required>

    <label for="department">Department:</label>
    <select name="department" required>
        <option value="">-- Select Department --</option>
        @foreach ($departments as $dept)
            <option value="{{ $dept }}">{{ $dept }}</option>
        @endforeach
    </select>

    <h3>Author Information</h3>
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" required>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" required>

    <button type="submit">Save Thesis</button>
</form>
