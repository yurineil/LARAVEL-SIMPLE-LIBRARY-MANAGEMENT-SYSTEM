@extends('layouts.app')

@section('title', 'Change Password — Library System')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-key me-2"></i>Change Password</h2>
    <p class="text-muted mb-0">Update your account password</p>
</div>

<div class="card table-card" style="max-width: 480px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Current Password</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autofocus>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">New Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm New Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>Update Password
            </button>
        </form>
    </div>
</div>
@endsection
