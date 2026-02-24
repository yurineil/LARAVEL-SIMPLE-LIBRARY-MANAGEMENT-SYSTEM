@extends('layouts.app')

@section('title', 'Borrow Books — Library System')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2><i class="bi bi-bookmark-plus me-2"></i>Borrow Books</h2>
        <p class="text-muted mb-0">You are borrowing as {{ Auth::user()->name }}. Add books from Browse Books, then set due date and submit.</p>
    </div>
    <a href="{{ route('student.books') }}" class="btn btn-outline-primary"><i class="bi bi-book me-1"></i>Browse Books</a>
</div>

@if($cartBooks->isEmpty())
    <div class="card table-card">
        <div class="card-body text-center py-5">
            <i class="bi bi-basket text-muted fs-1 mb-3 d-block"></i>
            <h5 class="mb-2">Your list is empty</h5>
            <p class="text-muted mb-4">Go to Browse Books and click <strong>Add to list</strong> on each book you want. They will appear here.</p>
            <a href="{{ route('student.books') }}" class="btn btn-primary"><i class="bi bi-book me-1"></i>Browse Books</a>
            <a href="{{ route('student.home') }}" class="btn btn-light ms-2">Back to Home</a>
        </div>
    </div>
@else
    <div class="card table-card" style="max-width: 640px;">
        <div class="card-body p-4">
            <p class="text-muted small mb-3"><i class="bi bi-info-circle me-1"></i>Books in your list ({{ $cartBooks->count() }}). Remove any you don’t want, set due date, then submit.</p>
            <ul class="list-group list-group-flush mb-4">
                @foreach($cartBooks as $book)
                    <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                        <span class="fw-semibold">{{ $book->title }}</span>
                        <a href="{{ route('student.borrow.remove', $book) }}" class="btn btn-sm btn-outline-danger" title="Remove from list"><i class="bi bi-x-lg"></i></a>
                    </li>
                @endforeach
            </ul>
            <form method="POST" action="{{ route('student.borrow.store') }}">
                @csrf
                @foreach($cartBooks as $book)
                    <input type="hidden" name="book_ids[]" value="{{ $book->id }}">
                @endforeach
                <div class="mb-4">
                    <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}" required>
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Submit Borrow Request</button>
                <a href="{{ route('student.books') }}" class="btn btn-outline-primary ms-2"><i class="bi bi-plus me-1"></i>Add more books</a>
                <a href="{{ route('student.home') }}" class="btn btn-light ms-2">Cancel</a>
            </form>
        </div>
    </div>
@endif
@endsection
