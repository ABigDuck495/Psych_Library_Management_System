@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Academic Paper</h2>

    <form action="{{ route('thesis.store') }}" method="POST">
        @csrf
        <label>Title</label>
        <input type="text" name="title" required>

        <label>Abstract</label>
        <textarea name="abstract" required></textarea>

        <label>Year Published</label>
        <input type="number" name="year_published" required>

        <label>Advisor</label>
        <input type="text" name="advisor" required>

        <label>Category</label>
        <select name="category" required>
            @foreach($categories as $cat)
                <option value="{{ is_object($cat) ? $cat->name : $cat }}">{{ is_object($cat) ? $cat->name : $cat }}</option>
            @endforeach
        </select>

        <h4>Authors</h4>
        <div>
            <input type="text" name="authors[0][first_name]" placeholder="First Name" required>
            <input type="text" name="authors[0][last_name]" placeholder="Last Name" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Save Academic Paper</button>
    </form>
</div>
@endsection
