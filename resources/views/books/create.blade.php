

<form action="{{ route('books.store') }}" method="POST">
    @csrf

    <label for="title">Title:</label>
    <input type="text" name="title" required>

    <label for="description">Description:</label>
    <textarea name="description" ></textarea>

    <label for="year_published">Year Published:</label>
    <input type="number" name="year_published" min="1900" max="{{ date('Y') }}" required>

    <label for="category_id">Category:</label>
    <select name="category_id" required>
        <option value="">-- Select Category --</option>
        @foreach ($categories as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
    <label for="copies_count">Number of Copies: </label>
    <input type="number" id="copies_count" name="copies_count" 
            value="{{ old('copies_count', 1) }}" min="1" max="20" required>
    @error('copies_count')<div>{{ $message }}</div>@enderror


    <h3>Author Information</h3>
    <label for="author_ids">Authors: </label>
    <select id="author_ids" name="author_ids[]" multiple required style="width: 300px; height: 150px;">
        @foreach($authors as $author)
            <option value="{{ $author->id }}"
                {{ in_array($author->id, old('author_ids', [])) ? 'selected' : '' }}>
                {{ $author->full_name }}
            </option>
        @endforeach
    </select>
    <div><small>Hold Ctrl to select multiple authors</small></div>
    @error('author_ids')<div>{{ $message }}</div>@enderror
    <a href="{{ route('authors.create', ['return_to' => url()->current()]) }}">
        <button type="button">Add New Author</button>
    </a>
    <button type="submit">Save Book</button>
</form>
