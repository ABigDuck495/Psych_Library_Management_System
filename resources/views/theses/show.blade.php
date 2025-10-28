@extends('layouts.app')

@section('title', $thesis->title . ' - Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Thesis Details and Information</h1>
        </div>
        
        <div>
            <a href="{{ route('theses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Theses
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Thesis Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Basic Information Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 info-card lg:col-span-2">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Thesis Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Title</label>
                        <p class="text-gray-800 font-medium">{{ $thesis->title }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Year Published</label>
                        <p class="text-gray-800 font-medium">{{ $thesis->year_published }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Available Copies</label>
                        <div class="flex items-center">
                            <span class="text-gray-800 font-medium">{{ $thesis->availableCopiesCount() }}</span>
                            @if($thesis->availableCopiesCount() > 0)
                                <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Available</span>
                            @else
                                <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Unavailable</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                        <span class="bg-purple-100 text-purple-800 department-badge">
                            {{ $thesis->department }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Authors</label>
                        <p class="text-gray-800 font-medium">{{ $thesis->authors_list ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Thesis ID</label>
                        <p class="text-gray-800 font-medium">#{{ $thesis->id }}</p>
                    </div>
                    <a href="{{ route('theses.viewPdf', $thesis->id) }}">view pdf</a>
                    <a href="{{ route('theses.downloadPdf', $thesis->id) }}">download pdf</a>
                </div>
            </div>
        </div>
        
        <!-- Request Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 info-card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-alt mr-2 text-green-600"></i>
                Request Thesis
            </h2>
            
            <div class="flex flex-col items-center mb-6">
                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                    <i class="fas fa-file-pdf text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 text-center">{{ Str::limit($thesis->title, 50) }}</h3>
                <p class="text-gray-600 text-center mt-1">{{ $thesis->department }}</p>
            </div>
            
            <div class="space-y-4">
                @auth
                    @if($thesis->canBeRequested() && !$thesis->hasUserRequested(Auth::id()))
                        <form action="{{ route('transactions.request-thesis', $thesis) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center transition shadow-md">
                                <i class="fas fa-file-alt mr-2"></i>
                                Request This Thesis
                            </button>
                        </form>
                    @elseif($thesis->hasUserRequested(Auth::id()))
                        @php
                            $activeTransaction = $thesis->transactions()
                                ->where('user_id', Auth::id())
                                ->where('transaction_status', 'requested')
                                ->latest()
                                ->first();
                        @endphp

                        @if($activeTransaction)
                            <form action="{{ route('transactions.cancel-request', $activeTransaction->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center transition shadow-md">
                                    <i class="fas fa-clock mr-2"></i>
                                    Cancel Request
                                </button>
                            </form>
                            <p class="text-sm text-gray-500 text-center">You have a pending request. You may cancel it.</p>
                        @else
                            <button disabled
                                    class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-times-circle mr-2"></i>
                                No Active Request Found
                            </button>
                        @endif
                    @else
                        <button disabled
                                class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center cursor-not-allowed">
                            <i class="fas fa-times-circle mr-2"></i>
                            No Copies Available
                        </button>
                        <p class="text-sm text-gray-500 text-center">Check back later for availability</p>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center transition shadow-md">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login to Request
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Abstract Section -->
    @if($thesis->abstract)
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 info-card">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-file-contract mr-2 text-purple-600"></i>
            Abstract
        </h2>
        
        <div class="prose max-w-none">
            <p class="text-gray-700 leading-relaxed">{{ $thesis->abstract }}</p>
        </div>
    </div>
    @endif

    <!-- Additional Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Authors Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 info-card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-users mr-2 text-indigo-600"></i>
                Authors
            </h2>
            
            <div class="space-y-3">
                @if($thesis->authors->isNotEmpty())
                    @foreach ($thesis->authors as $author)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $author->first_name }} {{ $author->last_name }}</h4>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-users text-4xl mb-2"></i>
                        <p>No authors information available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Thesis Details -->
        <div class="bg-white rounded-xl shadow-sm p-6 info-card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                Thesis Statistics
            </h2>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Total Copies</span>
                    <span class="font-medium text-gray-800">{{ $thesis->copies->count() ?? 1 }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Available Copies</span>
                    <span class="font-medium text-green-600">{{ $thesis->availableCopiesCount() }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Borrowed Copies</span>
                    <span class="font-medium text-orange-600">{{ ($thesis->copies->count() ?? 1) - $thesis->availableCopiesCount() }}</span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Publication Year</span>
                    <span class="font-medium text-gray-800">{{ $thesis->year_published }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons for Admin/Librarian -->
    @can('manage-theses')
    <div class="bg-white rounded-xl shadow-sm p-6 info-card">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-cog mr-2 text-red-600"></i>
            Management Actions
        </h2>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('theses.edit', $thesis) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-edit mr-2"></i>
                Edit Thesis
            </a>
            
            <form action="{{ route('theses.destroy', $thesis) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this thesis? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium flex items-center transition">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Thesis
                </button>
            </form>

            <a href="{{ route('theses.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-list mr-2"></i>
                View All Theses
            </a>
        </div>
    </div>
    @endcan
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
    
    .department-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
</style>
@endpush
@endsection