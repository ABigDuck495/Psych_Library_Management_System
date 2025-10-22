<div class="container mt-5">
    <h1 class="mb-4">Edit Book</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('books.index') }}" class="btn btn-secondary mb-3">← Back to Books List</a>

    <form action="{{ route('books.update', $book->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" name="title" id="title" class="form-control"
                   value="{{ old('title', $book->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $book->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="year_published" class="form-label">Year Published:</label>
            <input type="number" name="year_published" id="year_published" class="form-control"
                   min="1900" max="{{ date('Y') }}"
                   value="{{ old('year_published', $book->year_published) }}" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category:</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Select Category --</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}" {{ old('category_id', $book->category_id) == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Authors:</label>
            <label for="author_ids" class="form-label mt-2">Authors:</label>
            <select id="author_ids" name="author_ids[]" multiple class="form-select" style="min-height: 150px;">
                @foreach($authors as $author)
                    <option value="{{ $author->id }}"
                        {{ in_array($author->id, old('author_ids', $book->authors->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $author->first_name }} {{ $author->last_name }}
                    </option>
                @endforeach
            </select>
            <div class="mt-2">
                <a href="{{ route('authors.create', ['return_to' => url()->current()]) }}" class="btn btn-outline-secondary btn-sm">Add New Author</a>
            </div>
            @error('author_ids')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Book</button>
    </form>
</div>
