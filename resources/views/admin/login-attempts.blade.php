@extends('layouts.app')

@section('title', 'Login Attempts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Login Attempts Log</h3>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.login-attempts') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search">Search</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           value="{{ request('search') }}" placeholder="Email or IP Address">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Successful</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" 
                                           value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-sign-in-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Attempts</span>
                                    <span class="info-box-number">{{ $totalAttempts }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Successful</span>
                                    <span class="info-box-number">{{ $successfulCount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Failed</span>
                                    <span class="info-box-number">{{ $failedCount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-user-lock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Locked Accounts</span>
                                    <span class="info-box-number">{{ $lockedAccountsCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Login Attempts Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Failure Reason</th>
                                    <th>Attempted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attempts as $attempt)
                                    <tr>
                                        <td>{{ $attempt->id }}</td>
                                        <td>
                                            {{ $attempt->email }}
                                            @if($attempt->user)
                                                <br><small class="text-muted">{{ $attempt->user->full_name }}</small>
                                            @endif
                                        </td>
                                        <td><code>{{ $attempt->ip_address }}</code></td>
                                        <td>
                                            @if($attempt->successful)
                                                <span class="badge badge-success">Successful</span>
                                            @else
                                                <span class="badge badge-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$attempt->successful)
                                                <small class="text-danger">{{ $attempt->failure_reason }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $attempt->attempted_at->format('M d, Y h:i A') }}
                                            <br>
                                            <small class="text-muted">{{ $attempt->attempted_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    data-toggle="modal" 
                                                    data-target="#detailsModal{{ $attempt->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(in_array(auth()->user()->role, ['admin', 'super-admin']))
                                                <form action="{{ route('admin.login-attempts.destroy', $attempt) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Delete this login attempt record?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Details Modal -->
                                    <div class="modal fade" id="detailsModal{{ $attempt->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Login Attempt Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Email:</strong> {{ $attempt->email }}</p>
                                                    <p><strong>IP Address:</strong> {{ $attempt->ip_address }}</p>
                                                    <p><strong>Status:</strong> 
                                                        @if($attempt->successful)
                                                            <span class="badge badge-success">Successful</span>
                                                        @else
                                                            <span class="badge badge-danger">Failed</span>
                                                        @endif
                                                    </p>
                                                    @if(!$attempt->successful)
                                                        <p><strong>Failure Reason:</strong> {{ $attempt->failure_reason }}</p>
                                                    @endif
                                                    <p><strong>Attempted At:</strong> {{ $attempt->attempted_at->format('M d, Y h:i:s A') }}</p>
                                                    <p><strong>User Agent:</strong></p>
                                                    <code class="small">{{ $attempt->user_agent }}</code>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-search fa-2x text-muted mb-3"></i>
                                            <h5>No login attempts found</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $attempts->firstItem() ?? 0 }} to {{ $attempts->lastItem() ?? 0 }} 
                            of {{ $attempts->total() }} entries
                        </div>
                        <div>
                            {{ $attempts->links() }}
                        </div>
                    </div>
                </div>
                <!-- Card Footer -->
                <div class="card-footer">
                    <form action="{{ route('admin.login-attempts.cleanup') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm" 
                                onclick="return confirm('Delete login attempts older than 30 days?')">
                            <i class="fas fa-broom"></i> Cleanup Old Records
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection