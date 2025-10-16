<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white shadow-md p-6 rounded-lg space-y-4">
            @csrf @method('PUT')
            @include('users.partials.form')
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        </form>
    </div>
</x-app-layout>
