    <h1>{{ $author->full_name }}</h1>

    <h2>Books ({{ $author->bookCount }})</h2>
    @if($author->books->count())
        <ul>
            @foreach($author->books as $book)
                <li>{{ $book->title }}</li>
            @endforeach
        </ul>
    @else
        <p>No books found.</p>
    @endif

    <h2>Theses ({{ $author->thesisCount }})</h2>
    @if($author->theses->count())
        <ul>
            @foreach($author->theses as $thesis)
                <li>{{ $thesis->title }}</li>
            @endforeach
        </ul>
    @else
        <p>No theses found.</p>
    @endif

