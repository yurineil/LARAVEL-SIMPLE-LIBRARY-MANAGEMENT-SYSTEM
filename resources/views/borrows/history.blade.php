@extends('layouts.app')

@section('title', 'My Borrows — Library System')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-clock-history me-2"></i>My Borrowing History</h2>
    <p class="text-muted mb-0">Track your borrowed and returned books</p>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Book Title</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrows as $borrow)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $loop->iteration + ($borrows->currentPage() - 1) * $borrows->perPage() }}</td>
                        <td>
                            <a href="{{ route('books.show', $borrow->book) }}" class="fw-semibold text-decoration-none text-dark">
                                {{ $borrow->book->title }}
                            </a>
                            <div class="small text-muted">{{ $borrow->book->author }}</div>
                        </td>
                        <td>{{ $borrow->borrow_date->format('M d, Y') }}</td>
                        <td>{{ $borrow->return_date ? $borrow->return_date->format('M d, Y') : '—' }}</td>
                        <td>
                            @if($borrow->status === 'returned')
                                <span class="badge badge-returned"><i class="bi bi-check-circle me-1"></i>Returned</span>
                            @else
                                <span class="badge badge-borrowed"><i class="bi bi-exclamation-circle me-1"></i>Borrowed</span>
                            @endif
                        </td>
                        <td>
                            @if($borrow->status === 'borrowed')
                            <form method="POST" action="{{ route('return', $borrow) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success" onclick="return confirm('Return this book?')">
                                    <i class="bi bi-arrow-return-left me-1"></i>Return
                                </button>
                            </form>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2 mb-0">No borrowing history yet</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm mt-2">Browse Books</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $borrows->links('pagination::bootstrap-5') }}
</div>
@endsection
