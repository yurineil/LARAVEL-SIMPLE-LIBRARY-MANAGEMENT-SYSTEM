@extends('layouts.app')

@section('title', 'Users — Library System')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-people me-2"></i>User Management</h2>
    <p class="text-muted mb-0">View and manage all registered users</p>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar {{ $user->isAdmin() ? 'user-avatar-admin' : 'user-avatar-student' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->isAdmin())
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                    <i class="bi bi-shield-check me-1"></i>Admin
                                </span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                    <i class="bi bi-mortarboard me-1"></i>Student
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->isAdmin())
                                @if($user->isApproved())
                                    <span class="badge bg-success rounded-pill px-3">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Pending</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($user->isAdmin() && !$user->isApproved())
                                <form action="{{ route('users.approve', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve this admin">
                                        <i class="bi bi-check-lg me-1"></i>Approve
                                    </button>
                                </form>
                            @elseif(!$user->isAdmin())
                                <form action="{{ route('users.impersonate', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="View as this user">
                                        <i class="bi bi-person-check me-1"></i>View as
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $users->links('pagination::bootstrap-5') }}
</div>
@endsection
