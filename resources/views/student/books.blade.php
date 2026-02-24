@extends('layouts.app')

@section('title', 'Books — Library System')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2><i class="bi bi-book me-2"></i>Browse Books</h2>
        <p class="text-muted mb-0">View available books and borrow them.</p>
    </div>
    <a href="{{ route('student.borrow.create') }}" class="btn btn-primary"><i class="bi bi-bookmark-plus me-1"></i>Borrow Books</a>
</div>

<form method="GET" action="{{ route('student.books') }}" class="row g-2 align-items-end mb-3">
    <div class="col-12 col-md-4">
        <label class="form-label small text-muted mb-1"><i class="bi bi-search me-1"></i>Search</label>
        <input type="text" name="q" class="form-control" placeholder="Title, author, or category…" value="{{ request('q') }}">
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label small text-muted mb-1"><i class="bi bi-tag me-1"></i>Category</label>
        <select name="category" class="form-select">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-auto d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Apply</button>
        <a href="{{ route('student.books') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Clear</a>
    </div>
</form>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-bookmark-text me-1"></i>Title</th>
                        <th><i class="bi bi-person-lines-fill me-1"></i>Authors</th>
                        <th><i class="bi bi-tag me-1"></i>Category</th>
                        <th><i class="bi bi-box-seam me-1"></i>Available</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        @php $avail = $book->availableCopies(); @endphp
                        <tr>
                            <td class="fw-semibold">{{ $book->title }}</td>
                            <td>{{ $book->authors->pluck('name')->join(', ') ?: '—' }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $book->category }}</span></td>
                            <td>
                                <span class="badge {{ $avail > 0 ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3">{{ $avail }} / {{ $book->copies }}</span>
                            </td>
                            <td>
                                @if($avail > 0)
                                    <a href="{{ route('student.borrow.add', $book) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-circle me-1"></i>Add to list</a>
                                @else
                                    <span class="text-muted small"><i class="bi bi-dash-circle me-1"></i>Unavailable</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No books found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
