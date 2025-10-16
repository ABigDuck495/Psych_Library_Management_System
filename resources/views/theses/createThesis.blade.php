<!DOCTYPE html>
<html>
<head>
    <title>Add New Thesis</title>
</head>
<body>
    <h1>Add New Thesis</h1>
    
    <form action="{{ route('theses.store') }}" method="POST">
        @csrf
        
        <div>
            <label for="SKU">SKU *</label>
            <input type="text" id="SKU" name="SKU" value="{{ old('SKU') }}" required>
            @error('SKU')
                <div>{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <label for="dept_id">Department *</label>
            <select id="dept_id" name="dept_id" required>
                <option value="">Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" 
                        {{ old('dept_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->dept_name }}
                    </option>
                @endforeach
            </select>
            @error('dept_id')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="title">Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="abstract">Abstract *</label>
            <textarea id="abstract" name="abstract" rows="5" required>{{ old('abstract') }}</textarea>
            @error('abstract')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="year_published">Year Published *</label>
            <input type="number" id="year_published" name="year_published" 
                   value="{{ old('year_published', date('Y')) }}" 
                   min="1900" max="{{ date('Y') + 1 }}" required>
            @error('year_published')
                <div>{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <label for="advisor">Advisor</label>
            <input type="text" id="advisor" name="advisor" value="{{ old('advisor') }}">
            @error('advisor')
                <div>{{ $message }}</div>
            @enderror
        </div>
        
        <div>
            <label for="pages">Pages</label>
            <input type="number" id="pages" name="pages" value="{{ old('pages') }}" min="1">
            @error('pages')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
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
                @error('author_ids')
                    <div>{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <a href="{{ route('authors.create', ['return_to' => url()->current()]) }}">
                <button type="button">Add New Author</button>
            </a>
        </div>

        <div>
            <button type="submit">Create Thesis</button>
            <a href="{{ route('theses.index') }}">
                <button type="button">Cancel</button>
            </a>
        </div>
    </form>
</body>
</html>