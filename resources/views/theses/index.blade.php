


<div class="container mt-5">
    <h1 class="mb-4">Theses List</h1>

    {{-- Success message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Add new thesis button --}}
    <a href="{{ route('theses.create') }}" class="btn btn-primary mb-3">
        + Add New Thesis
    </a>

    {{-- Table --}}
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Abstract</th>
                <th>Year Published</th>
                <th>Department</th>
                <th>Authors</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($theses as $thesis)
                <tr>
                    <td>{{ $thesis->id }}</td>
                    <td>{{ $thesis->title }}</td>
                    <td style="max-width: 300px; white-space: pre-wrap;">{{ $thesis->abstract }}</td>
                    <td>{{ $thesis->year_published }}</td>
                    <td>{{ $thesis->department }}</td>
                    <td>
                        @if($thesis->authors->isNotEmpty())
                            <ul class="mb-0">
                                @foreach ($thesis->authors as $author)
                                    <li>{{ $author->first_name }} {{ $author->last_name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No authors linked</span>
                        @endif
                    </td>
<td>
    <a href="{{ route('theses.edit', $thesis->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <a href="{{ route('theses.show', $thesis->id) }}" class="btn btn-sm btn-info">View</a>

    <form action="{{ route('theses.destroy', $thesis->id) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this thesis?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            Delete
        </button>
    </form>
</td>

                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No theses found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

