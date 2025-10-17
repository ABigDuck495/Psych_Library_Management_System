<div class="container">
    <h2>{{ $book->title }}</h2>
    <p><strong>Category:</strong> {{ $book->category->category_name ?? 'Uncategorized' }}</p>
    <p><strong>Year Published:</strong> {{ $book->year_published }}</p>
    <p><strong>Description:</strong></p>
    <p>{{ $book->description }}</p>

    <h4>Authors:</h4>
    <ul>
        @foreach ($book->authors as $author)
            <li>{{ $author->first_name }} {{ $author->last_name }}</li>
        @endforeach
    </ul>

    <a href="{{ route('books.index') }}" class="btn btn-secondary">Back</a>
</div>
