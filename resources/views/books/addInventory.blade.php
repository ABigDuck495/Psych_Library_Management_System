<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inventory</title>
    <script>
        function showForm(type) {
            document.getElementById('bookForm').style.display = type === 'book' ? 'block' : 'none';
            document.getElementById('thesisForm').style.display = type === 'thesis' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>Add Inventory</h1>
    

    <button onclick="showForm('book')">Add Book</button>
    <button onclick="showForm('thesis')">Add Academic Paper</button>

    <hr>

    {{-- 📚 Book Form --}}
    <form id="bookForm" action="{{ route('books.store') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="type" value="book">

        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Author First Name:</label>
        <input type="text" name="author_firstname" required><br>

        <label>Author Last Name:</label>
        <input type="text" name="author_lastname" required><br>

        <label>Description:</label>
        <textarea name="description"></textarea><br>

        <label>Year Published:</label>
        <input type="text" name="year_published"><br>

        <label>Number of Copies:</label>
        <input type="number" name="num_copies" required><br>

        <label>Category:</label>
        <select name="category_id" required>
            @if(isset($categories) && count($categories) > 0)
                @foreach($categories as $category)
                    @if(!in_array($category->category_name, ['Thesis', 'Research Paper', 'Case Study', 'Narrative Report']))
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endif
                @endforeach
            @else
                <option value="">No categories available</option>
            @endif
        </select><br>

        <button type="submit">Save Book</button>
    </form>

    {{-- 📖 Academic Paper Form --}}
    <form id="thesisForm" action="{{ route('theses.store') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="type" value="thesis">

        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Author First Name:</label>
        <input type="text" name="author_firstname" required><br>

        <label>Author Last Name:</label>
        <input type="text" name="author_lastname" required><br>

        <label>Abstract:</label>
        <textarea name="abstract" required></textarea><br>

        <label>Year Published:</label>
        <input type="text" name="year_published" required><br>

        <label>Advisor:</label>
        <input type="text" name="advisor" required><br>

        <label>Category:</label>
        <select name="category_name" required>
            <option value="Thesis">Thesis</option>
            <option value="Research Paper">Research Paper</option>
            <option value="Case Study">Case Study</option>
            <option value="Narrative Report">Narrative Report</option>
        </select><br>

        <label>Department:</label>
        <select name="department_id" required>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select><br>

        <button type="submit">Save Academic Paper</button>
    </form>
</body>
</html>
