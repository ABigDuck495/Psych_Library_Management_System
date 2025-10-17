
<div class="container">
    <h2>{{ $thesis->title }}</h2>
    <p><strong>Department:</strong> {{ $thesis->department }}</p>
    <p><strong>Year Published:</strong> {{ $thesis->year_published }}</p>
    <p><strong>Abstract:</strong></p>
    <p>{{ $thesis->abstract }}</p>

    <h4>Authors:</h4>
    <ul>
        @foreach ($thesis->authors as $author)
            <li>{{ $author->first_name }} {{ $author->last_name }}</li>
        @endforeach
    </ul>

    <a href="{{ route('theses.index') }}" class="btn btn-secondary">Back</a>
</div>

