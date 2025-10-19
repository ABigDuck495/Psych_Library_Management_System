@if(session('success'))
    <div style="background: lightgreen; padding: 10px; margin: 10px 0;">
        {{ session('success') }}
    </div>
@endif

<a href=""></a>
<form action="{{ route('theses.store') }}" method="POST">


    @csrf

    <label for="title">Title:</label>
    <input type="text" name="title" required>

    <label for="abstract">Abstract:</label>
    <textarea name="abstract" required></textarea>

    <label for="year_published">Year Published:</label>
    <input type="number" name="year_published" min="1900" max="{{ date('Y') }}" required>

    <label for="department">Department *</label>
    <select id="department" name="department" required>
        <option value="AB Psychology">AB Psychology</option>
        <option value="BS Psychology">BS Psychology</option>
    </select>
    
    <label for="copies_count">Number of Copies: </label>
    <input type="number" id="copies_count" name="copies_count" 
            value="{{ old('copies_count', 1) }}" min="1" max="20" required>
    @error('copies_count')<div>{{ $message }}</div>@enderror


    <h3>Author Information</h3>
    <label for="author_ids">Authors *</label>
    <div>
        <select id="author_ids" name="author_ids[]" multiple required style="width: 300px; height: 150px;">
            @foreach($authors as $author)
                <option value="{{ $author->id }}"
                    {{ in_array($author->id, old('author_ids', [])) ? 'selected' : '' }}>
                    {{ $author->full_name }}
                </option>
            @endforeach
        </select>
        <div>
            <small>Hold Ctrl (or Cmd on Mac) to select multiple authors</small>
        </div>
        <div>
            <a href="{{ route('authors.create', ['return_to' => url()->current()]) }}">
                <button type="button">Add New Author</button>
            </a>
        </div>
        @error('author_ids')
            <div>{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">Save Thesis</button>
</form>
