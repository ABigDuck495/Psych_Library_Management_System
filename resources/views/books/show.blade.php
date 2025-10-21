<!DOCTYPE html>
<html>
<head>
    <title>{{ $book->title }}</title>
</head>
<body>
    <h1>{{ $book->title }}</h1>
    
    <!-- Display book details -->
    <div>
        <p><strong>Authors:</strong>
            {{ $book->authors->map(function($a) { return trim($a->first_name . ' ' . $a->last_name); })->implode(', ') ?: 'N/A' }}
        </p>
        <p><strong>Year Published:</strong> {{ $book->year_published }}</p>
        <p><strong>Category:</strong> {{ $book->category->category_name }}</p>
        <p><strong>Available Copies:</strong>{{ $book->availableCopies()->count() }}</p>
    </div>

    <!-- Request Button Section -->
    <div>
        @auth
            @if($book->canBeRequested() && !$book->hasUserRequested(Auth::id()))
                <form action="{{ route('transactions.request-book', $book) }}" method="POST">
                    @csrf
                    <button type="submit" style="background: blue; color: white; padding: 10px 20px;">
                        Request This Book
                    </button>
                </form>
            @elseif($book->hasUserRequested(Auth::id()))
                <button disabled style="background: orange; color: white; padding: 10px 20px;">
                    Request Pending
                </button>
            @else
                <button disabled style="background: gray; color: white; padding: 10px 20px;">
                    No Copies Available
                </button>
            @endif
            
            <!-- Link to view user's requests -->

        @else
            <!-- login shit but it dont work rn so -->
        @endauth
    </div>

    <!-- Display messages -->
    @if(session('success'))
        <div style="background: lightgreen; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: lightcoral; padding: 10px; margin: 10px 0;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Other book details... -->
</body>
</html>