@extends('layouts.app')

@section('title', 'User Details - ' . $user->first_name . ' ' . $user->last_name . ' | Psych Library')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">User Details</h1>
            <p class="text-gray-600 mt-2">View and manage user information</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Users
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Enhanced Action Buttons -->
    @if (Auth::user()->role === 'admin')
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">User Actions</h2>
        
        <div class="flex flex-wrap gap-3">
                <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-medium flex items-center transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-edit mr-2"></i>
                    Edit User
                </a>
                @if($user->account_status === 'Active')
                    <form action="{{ route('users.deactivate', $user) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" onclick="return confirm('Deactivate this user?')" class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-lg font-medium flex items-center transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-ban mr-2"></i>
                            Deactivate Account
                        </button>
                    </form>
                @else    
            @endif
                <form action="{{ route('users.activate', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-medium flex items-center transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-user-check mr-2"></i>
                        Activate Account
                    </button>
                </form>
            </div>
        </div>
@endif

    <!-- User Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Basic Information Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 info-card lg:col-span-2">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                Basic Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">University ID</label>
                        <p class="text-gray-800 font-medium">{{ $user->university_id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                        <p class="text-gray-800 font-medium">{{ $user->first_name }} {{ $user->last_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Username</label>
                        <p class="text-gray-800 font-medium">{{ $user->username }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                        <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                        <p class="text-gray-800 font-medium">{{ $user->phone_number ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                        @if($user->role === 'super-admin')
                            <span class="role-badge bg-purple-100 text-purple-800">Super Admin</span>
                        @elseif($user->role === 'admin')
                            <span class="role-badge bg-red-100 text-red-800">Admin</span>
                        @elseif($user->role === 'librarian')
                            <span class="role-badge bg-blue-100 text-blue-800">Librarian</span>
                        @else
                            <span class="role-badge bg-green-100 text-green-800">User</span>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Account Status</label>
                        @if($user->account_status === 'Active')
                            <span class="status-badge bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="status-badge bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">User Type</label>
                        <div class="flex items-center mt-1">
                            @if(isset($user->user_type) && $user->user_type === 'student')
                                <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                                <span class="text-gray-800 font-medium capitalize">Student</span>
                            @elseif(isset($user->user_type) && $user->user_type === 'employee')
                                <i class="fas fa-briefcase text-green-600 mr-2"></i>
                                <span class="text-gray-800 font-medium capitalize">Employee</span>
                            @else
                                <i class="fas fa-user text-gray-600 mr-2"></i>
                                <span class="text-gray-800 font-medium capitalize">{{ $user->user_type ?? 'Not Specified' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Profile Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 info-card">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-id-card mr-2 text-green-600"></i>
                Profile
            </h2>
            
            <div class="flex flex-col items-center mb-6">
                <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                    <i class="fas fa-user text-blue-600 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</h3>
                <p class="text-gray-600 capitalize">{{ $user->role }}</p>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Registration Date</span>
                    <span class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($user->registration_date ?? $user->created_at)->format('M d, Y') }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-500">Last Login</span>
                    <span class="text-gray-800 font-medium">
                        @if($user->last_login_at)
                            {{ \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y H:i') }}
                        @else
                            Never
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-500">Account Created</span>
                    <span class="text-gray-800 font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-500">Last Updated</span>
                    <span class="text-gray-800 font-medium">{{ $user->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Information - Expanded to full width -->
    <div class="bg-white rounded-xl shadow-sm p-6 info-card mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2 text-gray-600"></i>
            Additional Information
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- User Type Specific Information -->
            @if(isset($user->user_type) && $user->user_type === 'student')
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-university mr-2 text-blue-600"></i>
                    Student Information
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Academic Program</label>
                        <p class="text-gray-800 font-medium">{{ $user->student->academic_program ?? 'Not Specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Major/Department</label>
                        <p class="text-gray-800 font-medium">{{ $user->student->department ?? 'Not Specified' }}</p>
                    </div>
                </div>
            </div>
            @elseif(isset($user->user_type) && $user->user_type === 'employee')
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-briefcase mr-2 text-green-600"></i>
                    Employee Information
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                        <p class="text-gray-800 font-medium">{{ $user->employee->department ?? 'Not Specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Position Title</label>
                        <p class="text-gray-800 font-medium">{{ $user->employee->position_title ?? 'Not Specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Employee ID</label>
                        <p class="text-gray-800 font-medium">{{ $user->university_id ?? 'Not Specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Employment Status</label>
                        <span class="status-badge bg-blue-100 text-blue-800">{{ $user->employment_status ?? 'Active' }}</span>
                    </div>
                </div>
            </div>
            @else
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-user-tag mr-2 text-gray-600"></i>
                    User Type Information
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">User Type</label>
                        <p class="text-gray-800 font-medium capitalize">{{ $user->user_type ?? 'Not Specified' }}</p>
                    </div>
                </div>
            </div>
            @endif

        
        @if(!(isset($user->user_type) && in_array($user->user_type, ['student', 'employee'])))
        <div class="mt-6 text-center py-4 text-gray-400 border-t border-gray-200">
            <i class="fas fa-user-tag text-3xl mb-2"></i>
            <p>Basic user account with standard permissions</p>
        </div>
        @endif
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
    
    .role-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success message after 5 seconds
        const successMessage = document.querySelector('.bg-green-100');
        if (successMessage) {
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 5000);
        }
    });
</script>
@endpush
@endsection