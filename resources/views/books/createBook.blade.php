@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Book</h2>

    <form action="{{ route('book.store') }}" method="POST">
        @csrf
        <label>Title</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <label>Year Published</label>
        <input type="number" name="year_published">

        <label>Category</label>
        <select name="category_id" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <label>Number of Copies</label>
        <input type="number" name="copies" min="1" required>

        <h4>Authors</h4>
        <div>
            <input type="text" name="authors[0][first_name]" placeholder="First Name" required>
            <input type="text" name="authors[0][last_name]" placeholder="Last Name" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Save Book</button>
    </form>
</div>
@endsection
