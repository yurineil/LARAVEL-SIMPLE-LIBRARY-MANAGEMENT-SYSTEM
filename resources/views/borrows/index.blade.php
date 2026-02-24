@extends('layouts.app')

@section('title', 'Borrows — Library System')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2><i class="bi bi-arrow-left-right me-2"></i>Borrowing Records</h2>
        <p class="text-muted mb-0">Record and process student borrows and returns. Fine = ₱10 × overdue days × number of books.</p>
    </div>
    <a href="{{ route('borrows.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Record Borrow
    </a>
</div>

<form method="GET" class="mb-3 row g-2 align-items-end">
    <div class="col-auto">
        <label class="form-label mb-0 small">Filter by student</label>
        <select name="student_id" class="form-select form-select-sm" style="width: 220px;">
            <option value="">All students</option>
            @foreach(\App\Models\Student::orderBy('name')->get() as $s)
                <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
    </div>
</form>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Books</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrows as $borrow)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $loop->iteration + ($borrows->currentPage() - 1) * $borrows->perPage() }}</td>
                        <td>
                            <div class="fw-semibold">{{ $borrow->student->name }}</div>
                            @if($borrow->student->email)
                                <div class="small text-muted">{{ $borrow->student->email }}</div>
                            @endif
                        </td>
                        <td>
                            @foreach($borrow->borrowItems as $item)
                                <div class="small">
                                    {{ $item->book->title }}
                                    @if($item->returned_at)
                                        <span class="text-success">(returned)</span>
                                    @else
                                        <span class="text-danger">(out)</span>
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        <td>{{ $borrow->borrow_date->format('M d, Y') }}</td>
                        <td>{{ $borrow->due_date->format('M d, Y') }}</td>
                        <td>
                            @if($borrow->hasOutstanding())
                                @if($borrow->overdueDays() > 0)
                                    <span class="badge badge-status bg-danger rounded-pill" title="Books not yet returned, past due date">Overdue ({{ $borrow->overdueDays() }} day{{ $borrow->overdueDays() !== 1 ? 's' : '' }})</span>
                                @else
                                    <span class="badge badge-status badge-borrowed rounded-pill" title="Books still out">In use</span>
                                @endif
                            @else
                                <span class="badge badge-status badge-returned rounded-pill" title="All books returned">Returned</span>
                            @endif
                        </td>
                        <td>
                            @if($borrow->currentFine() > 0)
                                <span class="text-danger fw-semibold">₱{{ number_format($borrow->currentFine(), 2) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($borrow->hasOutstanding())
                                <a href="{{ route('borrows.return', $borrow) }}" class="btn btn-sm btn-success">Return (partial/ full)</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No borrowing records yet.</td>
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
