@extends('layouts.guest-student')

@section('title', 'Borrow Books — Library System')

@section('content')
<div class="page-header mb-4">
    <h2><i class="bi bi-bookmark-plus me-2"></i>Borrow Books</h2>
    <p class="text-muted mb-0">Select books and due date. You are borrowing as {{ $student->name }} ({{ $student->student_id }}).</p>
</div>

@if($books->isEmpty())
    <div class="card table-card">
        <div class="card-body text-center py-5">
            <p class="text-muted mb-0">No books available to borrow at the moment.</p>
            <a href="{{ route('guest.borrows') }}" class="btn btn-outline-primary mt-3">Back to My Borrows</a>
        </div>
    </div>
@else
    <div class="card table-card" style="max-width: 640px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('guest.borrow.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">Books <span class="text-danger">*</span></label>
                    <p class="small text-muted">Select one or more books. Only books with available copies are listed.</p>
                    <div class="border rounded p-3" style="max-height: 280px; overflow-y: auto;">
                        @foreach($books as $book)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="book_ids[]" value="{{ $book->id }}" id="book{{ $book->id }}">
                                <label class="form-check-label" for="book{{ $book->id }}">{{ $book->title }} — {{ $book->availableCopies() }} available</label>
                            </div>
                        @endforeach
                    </div>
                    @error('book_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}" required>
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Submit Borrow Request</button>
                <a href="{{ route('guest.borrows') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
@endif
@endsection
