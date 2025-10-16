{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">Welcome to Psych Library Management System</h1>

                {{-- Admin / Super Admin View --}}
                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'super-admin')
                    <div class="mb-6">
                        <h3 class="font-semibold mb-3 text-lg">Admin / Super Admin Dashboard</h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('authors.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                Go to Authors
                            </a>
                            <a href="{{ url('/catalogue') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                Go to Catalogue
                            </a>
                            <a href="{{ route('users.index') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-800 transition">
                                Go to Users
                            </a>

                        </div>
                    </div>
                @endif

                {{-- Regular User / Student / Staff View --}}
                @if (Auth::user()->role === 'user')
                    <div>
                        <h3 class="font-semibold mb-3 text-lg">User Dashboard</h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ url('/catalogue') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                View Catalogue
                            </a>
                            <a href="{{ route('authors.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                View Authors
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
