@extends('layouts.app')

@section('title', 'Books — Library System')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2><i class="bi bi-book me-2"></i>Books</h2>
        <p class="text-muted mb-0">List of books with available inventory</p>
    </div>
    <a href="{{ route('books.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Book
    </a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group" style="max-width: 320px;">
        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Search title or category...">
        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
    </div>
</form>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Authors</th>
                        <th>Category</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}</td>
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="fw-semibold text-decoration-none text-dark">{{ $book->title }}</a>
                        </td>
                        <td>{{ $book->authors->pluck('name')->join(', ') ?: '—' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $book->category }}</span></td>
                        <td>
                            @php $avail = $book->availableCopies(); @endphp
                            <span class="badge {{ $avail > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">{{ $avail }} / {{ $book->copies }}</span>
                        </td>
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            <form method="POST" action="{{ route('books.destroy', $book) }}" class="d-inline" onsubmit="return confirm('Delete this book?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No books found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $books->links('pagination::bootstrap-5') }}
</div>
@endsection
