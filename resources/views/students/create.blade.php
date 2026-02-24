@extends('layouts.app')

@section('title', 'Add Student — Library System')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none">Students</a></li>
            <li class="breadcrumb-item active">Add Student</li>
        </ol>
    </nav>
    <h2><i class="bi bi-person-plus me-2"></i>Add Student</h2>
    <p class="text-muted mb-0">Students do not log in; they are selected when recording borrows.</p>
</div>

<div class="card table-card" style="max-width: 520px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Student ID / Number</label>
                <input type="text" class="form-control @error('student_id') is-invalid @enderror" name="student_id" value="{{ old('student_id') }}" placeholder="Optional">
                @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Add Student</button>
            <a href="{{ route('students.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
