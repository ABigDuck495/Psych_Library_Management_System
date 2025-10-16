
<div class="container mt-5">
    <h2>Categories</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">+ Add Category</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Created At</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->category_name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>{{ $category->created_at ? $category->created_at->format('Y-m-d') : '—' }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No categories found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $categories->links() }}
</div>

