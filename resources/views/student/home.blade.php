@extends('layouts.app')

@section('title', 'Student — Library System')

@section('content')
<div class="page-header">
    <h2><i class="bi bi-person-badge me-2"></i>Student</h2>
    <p class="text-muted mb-0">You are logged in as a student. You can borrow books and view your borrows here.</p>
</div>
<div class="row g-4">
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('student.books') }}" class="text-decoration-none">
            <div class="card table-card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-book fs-3 text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark">Browse Books</div>
                        <small class="text-muted">View and borrow available books</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('student.borrow.create') }}" class="text-decoration-none">
            <div class="card table-card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3">
                        <i class="bi bi-bookmark-plus fs-3 text-success"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark">Borrow Books</div>
                        <small class="text-muted">Select books and submit a borrow</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('student.my-borrows') }}" class="text-decoration-none">
            <div class="card table-card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info bg-opacity-10 p-3">
                        <i class="bi bi-journal-bookmark fs-3 text-info"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark">My Borrows</div>
                        <small class="text-muted">View your borrowing history</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="card table-card border-0 shadow-sm mt-4">
    <div class="card-body p-4">
        <p class="mb-0">Welcome, <strong>{{ Auth::user()->name }}</strong>. Use the cards above or the menu to borrow books and see your loans.</p>
    </div>
</div>
@endsection
