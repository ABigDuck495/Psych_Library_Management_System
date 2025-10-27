@extends('layouts.app')

@section('title', $thesis->title . ' - Psych Library')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $thesis->title }}</h1>
    
    <!-- Thesis Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <p class="mb-2"><strong>Authors:</strong> {{ $thesis->authors_list ?? 'N/A' }}</p>
            <p class="mb-2"><strong>Year Published:</strong> {{ $thesis->year_published }}</p>
            <p class="mb-2"><strong>Department:</strong> {{ $thesis->department }}</p>
            <p class="mb-2"><strong>Available Copies:</strong> {{ $thesis->availableCopiesCount() }}</p>
        </div>
        
        @if($thesis->abstract)
        <div>
            <h3 class="text-lg font-semibold mb-2">Abstract</h3>
            <p class="text-gray-700">{{ $thesis->abstract }}</p>
        </div>
        @endif
    </div>

    <!-- Request Button Section -->
    <div class="mt-6 border-t pt-6">
        @auth
            @if($thesis->canBeRequested() && !$thesis->hasUserRequested(Auth::id()))
                <form action="{{ route('transactions.request-thesis', $thesis) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-file-alt mr-2"></i>Request This Thesis
                    </button>
                </form>
            @elseif($thesis->hasUserRequested(Auth::id()))
                <button disabled class="bg-orange-500 text-white px-6 py-2 rounded-lg font-medium cursor-not-allowed">
                    <i class="fas fa-clock mr-2"></i>Request Pending
                </button>
            @else
                <button disabled class="bg-gray-400 text-white px-6 py-2 rounded-lg font-medium cursor-not-allowed">
                    <i class="fas fa-times-circle mr-2"></i>No Copies Available
                </button>
            @endif
        @else
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                <i class="fas fa-sign-in-alt mr-2"></i>Login to Request
            </a>
        @endauth
    </div>

    <!-- Display messages -->
    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Action Buttons for Admin/Librarian -->
    @can('manage-theses')
    <div class="mt-6 border-t pt-6 flex space-x-3">
        <a href="{{ route('theses.edit', $thesis) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition">
            <i class="fas fa-edit mr-2"></i>Edit Thesis
        </a>
        
        <form action="{{ route('theses.destroy', $thesis) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this thesis?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition">
                <i class="fas fa-trash mr-2"></i>Delete Thesis
            </button>
        </form>
    </div>
    @endcan
</div>
@endsection