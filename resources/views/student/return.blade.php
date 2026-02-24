@extends('layouts.app')

@section('title', 'Return Books — Library System')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('student.my-borrows') }}" class="text-decoration-none">My Borrows</a></li>
            <li class="breadcrumb-item active">Return Books</li>
        </ol>
    </nav>
    <h2><i class="bi bi-arrow-down-left-square me-2"></i>Return Books</h2>
    <p class="text-muted mb-0">Mark which books you are returning. Fine = ₱10 × overdue days × number of books returned.</p>
</div>

<div class="card table-card mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-2">Borrow details</h5>
        <p class="mb-1"><strong>Borrow date:</strong> {{ $borrow->borrow_date->format('M d, Y') }}</p>
        <p class="mb-1"><strong>Due date:</strong> {{ $borrow->due_date->format('M d, Y') }}</p>
        @php $potentialFine = \App\Models\Borrow::computeFine($borrow->due_date, now()->toDateString(), $outstanding->count()); @endphp
        @if($potentialFine > 0)
            <p class="mb-0"><strong>Fine if returned today:</strong> <span class="text-danger fw-semibold">₱{{ number_format($potentialFine, 2) }}</span> <span class="small text-muted">(₱10 × {{ $borrow->overdueDays() }} day(s) × {{ $outstanding->count() }} book(s))</span></p>
        @else
            <p class="mb-0 text-muted small">No fine if returned today (on or before due date).</p>
        @endif
    </div>
</div>

<div class="card table-card" style="max-width: 560px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('student.return.process') }}">
            @csrf
            <input type="hidden" name="borrow_id" value="{{ $borrow->id }}">
            <div class="mb-3">
                <label class="form-label fw-semibold">Return Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('return_date') is-invalid @enderror" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" required>
                @error('return_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Books to return <span class="text-danger">*</span></label>
                <p class="small text-muted">Select the books you are returning (partial or full).</p>
                @foreach($outstanding as $item)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="item_ids[]" value="{{ $item->id }}" id="item{{ $item->id }}">
                        <label class="form-check-label" for="item{{ $item->id }}">{{ $item->book->title }}</label>
                    </div>
                @endforeach
                @error('item_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Confirm Return</button>
            <a href="{{ route('student.my-borrows') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>
@endsection
