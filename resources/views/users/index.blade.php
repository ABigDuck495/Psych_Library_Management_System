<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            @if(session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + Add User
            </a>

            <table class="min-w-full mt-4 border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $user->id }}</td>
                            <td class="px-4 py-2">{{ $user->fullName }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ ucfirst($user->role) }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline" onclick="return confirm('Delete user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h3 class="mt-8 font-semibold text-lg">Password Reset Requests</h3>
            <table class="min-w-full mt-2 border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">User</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Requested At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td class="px-4 py-2">{{ $req->user->fullName }}</td>
                            <td class="px-4 py-2">{{ ucfirst($req->status) }}</td>
                            <td class="px-4 py-2">{{ $req->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
