@extends('layouts.app')

@section('title', 'Edit Student — Library System')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none">Students</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
    <h2><i class="bi bi-pencil me-2"></i>Edit Student</h2>
</div>

<div class="card table-card" style="max-width: 520px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $student->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $student->email) }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Student ID / Number</label>
                <input type="text" class="form-control @error('student_id') is-invalid @enderror" name="student_id" value="{{ old('student_id', $student->student_id) }}">
                @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('students.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
