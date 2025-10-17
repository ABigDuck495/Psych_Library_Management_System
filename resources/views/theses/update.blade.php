<div class="container mt-5">
    <h1 class="mb-4">Edit Thesis</h1>

    {{-- Success or error messages --}}
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

    {{-- Back button --}}
    <a href="{{ route('theses.index') }}" class="btn btn-secondary mb-3">← Back to Theses List</a>

    {{-- Edit form --}}
    <form action="{{ route('theses.update', $thesis->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" name="title" id="title" class="form-control"
                   value="{{ old('title', $thesis->title) }}" required>
        </div>

        {{-- Abstract --}}
        <div class="mb-3">
            <label for="abstract" class="form-label">Abstract:</label>
            <textarea name="abstract" id="abstract" class="form-control" rows="4" required>{{ old('abstract', $thesis->abstract) }}</textarea>
        </div>

        {{-- Year Published --}}
        <div class="mb-3">
            <label for="year_published" class="form-label">Year Published:</label>
            <input type="number" name="year_published" id="year_published" class="form-control"
                   min="1900" max="{{ date('Y') }}"
                   value="{{ old('year_published', $thesis->year_published) }}" required>
        </div>

        {{-- Department Dropdown --}}
        <div class="mb-3">
            <label for="department" class="form-label">Department:</label>
            <select name="department" id="department" class="form-select" required>
                <option value="">-- Select Department --</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept }}" {{ old('department', $thesis->department) == $dept ? 'selected' : '' }}>
                        {{ $dept }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Author List (read-only display) --}}
        <div class="mb-3">
            <label class="form-label">Current Authors:</label>
            @if($thesis->authors->isNotEmpty())
                <ul>
                    @foreach ($thesis->authors as $author)
                        <li>{{ $author->first_name }} {{ $author->last_name }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No authors linked to this thesis.</p>
            @endif
        </div>

        {{-- Submit button --}}
        <button type="submit" class="btn btn-success">Update Thesis</button>
    </form>
</div>
