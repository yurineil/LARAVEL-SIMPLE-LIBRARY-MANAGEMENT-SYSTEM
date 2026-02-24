@extends('layouts.app')

@section('title', 'Authors — Library System')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2><i class="bi bi-pencil-square me-2"></i>Authors</h2>
        <p class="text-muted mb-0">Manage authors; books can have multiple authors</p>
    </div>
    <a href="{{ route('authors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Author
    </a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group" style="max-width: 320px;">
        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Search authors...">
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
                        <th>Name</th>
                        <th>Books</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $loop->iteration + ($authors->currentPage() - 1) * $authors->perPage() }}</td>
                        <td class="fw-semibold">{{ $author->name }}</td>
                        <td>{{ $author->books_count }}</td>
                        <td>
                            <a href="{{ route('authors.edit', $author) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('authors.destroy', $author) }}" class="d-inline" onsubmit="return confirm('Delete this author?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">No authors found. Add authors before adding books.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $authors->links('pagination::bootstrap-5') }}
</div>
@endsection
