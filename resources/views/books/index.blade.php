<div class="container mt-5">
    <h1 class="mb-4">Books List</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">+ Add New Book</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Year Published</th>
                <th>Category</th>
                <th>Authors</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td style="max-width: 300px; white-space: pre-wrap;">{{ $book->description }}</td>
                    <td>{{ $book->year_published }}</td>
                    <td>{{ $book->category->category_name ?? 'Uncategorized' }}</td>
                    <td>
                        @if($book->authors->isNotEmpty())
                            <ul class="mb-0">
                                @foreach ($book->authors as $author)
                                    <li>{{ $author->first_name }} {{ $author->last_name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No authors linked</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-info">View</a>

                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No books found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
