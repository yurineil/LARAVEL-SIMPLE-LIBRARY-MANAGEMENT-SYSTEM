@extends('layouts.app')

@section('title', 'Record Borrow — Library System')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('borrows.index') }}" class="text-decoration-none">Borrows</a></li>
            <li class="breadcrumb-item active">Record Borrow</li>
        </ol>
    </nav>
    <h2><i class="bi bi-bookmark-plus me-2"></i>Record Borrow</h2>
    <p class="text-muted mb-0">Select a student and books; set due date. Students do not log in.</p>
</div>

<div class="card table-card" style="max-width: 560px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('borrows.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Student <span class="text-danger">*</span></label>
                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                    <option value="">Select student</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}{{ $s->student_id ? ' (' . $s->student_id . ')' : '' }}</option>
                    @endforeach
                </select>
                @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}" required>
                @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Books <span class="text-danger">*</span></label>
                <select name="book_ids[]" class="form-select @error('book_ids') is-invalid @enderror" multiple size="8">
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ in_array($book->id, old('book_ids', [])) ? 'selected' : '' }}
                            {{ $book->availableCopies() < 1 ? 'disabled' : '' }}>
                            {{ $book->title }} — {{ $book->availableCopies() }} available
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple. Only books with available copies are borrowable.</small>
                @error('book_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Record Borrow</button>
            <a href="{{ route('borrows.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
