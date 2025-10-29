@extends('layouts.app')

@section('title', $author->full_name . ' - Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $author->full_name }}</h1>
            <p class="text-gray-600 mt-2">Author Profile</p>
        </div>
        
        <div>
            <a href="{{ route('authors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Authors
            </a>
        </div>
    </div>

    <!-- Author's Works -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Books Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-book mr-2 text-green-600"></i>
                Books ({{ $author->bookCount }})
            </h2>
            
            @if($author->books->count())
                <div class="space-y-4">
                    @foreach($author->books as $book)
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-start mb-3">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-book text-green-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800">{{ $book->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Published: {{ $book->year_published ?? 'Unknown' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Co-authors for this book -->
                            @if($book->authors->count() > 1)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Co-authors:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($book->authors as $coAuthor)
                                            @if($coAuthor->id != $author->id)
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                    {{ $coAuthor->full_name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-book-open text-4xl mb-3"></i>
                    <p class="text-lg">No books found</p>
                </div>
            @endif
        </div>

        <!-- Theses Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                Theses ({{ $author->thesisCount }})
            </h2>
            
            @if($author->theses->count())
                <div class="space-y-4">
                    @foreach($author->theses as $thesis)
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-start mb-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-file-alt text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800">{{ $thesis->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Published: {{ $thesis->year_published ?? 'Unknown' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Co-authors for this thesis -->
                            @if($thesis->authors->count() > 1)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Co-authors:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($thesis->authors as $coAuthor)
                                            @if($coAuthor->id != $author->id)
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                    {{ $coAuthor->full_name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-file-alt text-4xl mb-3"></i>
                    <p class="text-lg">No theses found</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .info-card {
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    
    .info-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
@endpush
@endsection