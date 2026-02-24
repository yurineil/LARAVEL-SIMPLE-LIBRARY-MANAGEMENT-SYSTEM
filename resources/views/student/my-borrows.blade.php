@extends('layouts.app')

@section('title', 'My Borrows — Library System')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2><i class="bi bi-journal-bookmark me-2"></i>My Borrows</h2>
        <p class="text-muted mb-0">Your borrowing history and current loans. Fine = ₱10 × overdue days × number of books.</p>
    </div>
    <a href="{{ route('student.borrow.create') }}" class="btn btn-primary"><i class="bi bi-bookmark-plus me-1"></i>Borrow Books</a>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Books</th>
                        <th>Status</th>
                        <th>Fine</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrows as $borrow)
                        @php
                            $outstanding = $borrow->borrowItems->where('returned_at', null)->count();
                        @endphp
                        <tr>
                            <td>{{ $borrow->borrow_date->format('M d, Y') }}</td>
                            <td>{{ $borrow->due_date->format('M d, Y') }}</td>
                            <td>
                                @foreach($borrow->borrowItems as $item)
                                    <span class="d-block">{{ $item->book->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($outstanding === 0)
                                    <span class="badge bg-success rounded-pill">All returned</span>
                                @elseif($borrow->overdueDays() > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $borrow->overdueDays() }} day{{ $borrow->overdueDays() !== 1 ? 's' : '' }} overdue</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $outstanding }} out</span>
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
                                @if($outstanding > 0)
                                    <a href="{{ route('student.return.form', $borrow) }}" class="btn btn-sm btn-success"><i class="bi bi-arrow-down-left-square me-1"></i>Return books</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">You have no borrows yet. <a href="{{ route('student.borrow.create') }}">Borrow books</a></td>
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
