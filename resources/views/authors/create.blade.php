@extends('layouts.app')

@section('title', 'Add Author — Library System')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('authors.index') }}" class="text-decoration-none">Authors</a></li>
            <li class="breadcrumb-item active">Add Author</li>
        </ol>
    </nav>
    <h2><i class="bi bi-pencil-square me-2"></i>Add Author</h2>
</div>

<div class="card table-card" style="max-width: 480px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('authors.store') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Add Author</button>
            <a href="{{ route('authors.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
