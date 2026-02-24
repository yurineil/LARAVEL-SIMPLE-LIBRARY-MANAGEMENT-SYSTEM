@extends('layouts.app')

@section('title', $book->title . ' — Library System')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Books</a></li>
            <li class="breadcrumb-item active">{{ $book->title }}</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="fw-bold mb-1">{{ $book->title }}</h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-pencil-square me-1"></i>
                            {{ $book->authors->pluck('name')->join(', ') ?: 'No authors' }}
                        </p>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fs-6">{{ $book->category }}</span>
                </div>
                <hr>
                <h6 class="fw-bold text-muted text-uppercase small mb-2">Description</h6>
                <p class="text-dark" style="line-height: 1.8;">{{ $book->description ?: 'No description available.' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card stat-card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Inventory</h5>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Total copies</span>
                    <strong>{{ $book->copies }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Borrowed</span>
                    <strong class="text-danger">{{ $book->borrowedCount() }}</strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Available</span>
                    <span class="badge {{ $book->availableCopies() > 0 ? 'badge-returned' : 'badge-borrowed' }} fs-6">{{ $book->availableCopies() }}</span>
                </div>
            </div>
        </div>
        <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-primary w-100 mt-2">Edit Book</a>
    </div>
</div>
@endsection
