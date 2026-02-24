@extends('layouts.app')

@section('title', 'Edit Author — Library System')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('authors.index') }}" class="text-decoration-none">Authors</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
    <h2><i class="bi bi-pencil me-2"></i>Edit Author</h2>
</div>

<div class="card table-card" style="max-width: 480px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('authors.update', $author) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $author->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('authors.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
