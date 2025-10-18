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
            @if($book->authors->isNotEmpty())
                <ul>
                    @foreach ($book->authors as $author)
                        <li>{{ $author->first_name }} {{ $author->last_name }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No authors linked to this book.</p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update Book</button>
    </form>
</div>
