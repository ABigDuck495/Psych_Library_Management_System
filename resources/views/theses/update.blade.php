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
            <label class="form-label">Authors</label>
            <select id="author_ids" name="author_ids[]" multiple class="form-select" style="min-height: 150px;">
                @foreach($authors as $author)
                    <option value="{{ $author->id }}"
                        {{ in_array($author->id, old('author_ids', $thesis->authors->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $author->first_name }} {{ $author->last_name }}
                    </option>
                @endforeach
            </select>
            <div class="mt-2">
                <a href="{{ route('authors.create', ['return_to' => url()->current()]) }}" class="bg-gray-200 px-3 py-1 rounded text-sm">Add New Author</a>
            </div>
            @error('author_ids')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Copies Count (allow modifying number of copies similar to create) --}}
        <div class="mb-3">
            <label for="copies_count" class="form-label">Number of Copies</label>
            <input type="number" id="copies_count" name="copies_count" value="{{ old('copies_count', $thesis->copies_count ?? 1) }}" min="1" max="20" class="form-control" />
            @error('copies_count')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit button --}}
        <button type="submit" class="btn btn-success">Update Thesis</button>
    </form>
</div>
