<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Interface - Psych Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-xl shadow-lg w-2/3 text-center">
        <h1 class="text-3xl font-bold mb-4 text-green-800">Librarian Interface</h1>
        <p class="text-gray-600 mb-8">
            Welcome, {{ Auth::user()->first_name }}! You have <strong>Librarian</strong> level access.
        </p>

        <div class="space-x-4">
            <a href="{{ route('books.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Manage Books</a>
            <a href="{{ route('theses.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Manage Theses</a>
            <a href="{{ route('transactions.index') }}" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Transactions</a>
        </div>

        <div class="mt-10">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-red-500 font-medium">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </div>
</body>
</html>
