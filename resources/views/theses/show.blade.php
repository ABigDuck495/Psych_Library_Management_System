<!DOCTYPE html>
<html>
<head>
    <title>{{ $thesis->title }}</title>
</head>
<body>
    <h1>{{ $thesis->title }}</h1>
    
    <!-- Display thesis details -->
    <div>
        <p><strong>Authors:</strong> {{ $thesis->authors_list }}</p>
        <p><strong>Year Published:</strong> {{ $thesis->year_published }}</p>
        <p><strong>Available Copies:</strong> {{ $thesis->availableCopies()->count() }}</p>
    </div>

    <!-- Request Button Section -->
    <div>
        @auth
            @if($thesis->canBeRequested() && !$thesis->hasUserRequested(Auth::id()))
                <form action="{{ route('transactions.request-thesis', $thesis) }}" method="POST">
                    @csrf
                    <button type="submit" style="background: blue; color: white; padding: 10px 20px;">
                        Request This thesis
                    </button>
                </form>
            @elseif($thesis->hasUserRequested(Auth::id()))
                <button disabled style="background: orange; color: white; padding: 10px 20px;">
                    Request Pending
                </button>
            @else
                <button disabled style="background: gray; color: white; padding: 10px 20px;">
                    No Copies Available
                </button>
            @endif
            
            <!-- Link to view user's requests -->
            <!--  -->
        @else
            
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

    <!-- Other thesis details... -->
</body>
</html>