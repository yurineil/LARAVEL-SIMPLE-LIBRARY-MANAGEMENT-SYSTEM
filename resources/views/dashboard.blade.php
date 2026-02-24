<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold text-dark mb-0">
            <i class="bi bi-speedometer2 me-2"></i>{{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="page-header mb-4">
        <p class="text-muted mb-0">Welcome to the Library Management System.</p>
    </div>

    <div class="row g-4">
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('students.index') }}" class="text-decoration-none">
                <div class="card table-card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-person-vcard fs-3 text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Students</div>
                            <small class="text-muted">Manage students</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('authors.index') }}" class="text-decoration-none">
                <div class="card table-card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 p-3">
                            <i class="bi bi-pencil-square fs-3 text-success"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Authors</div>
                            <small class="text-muted">Manage authors</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('books.index') }}" class="text-decoration-none">
                <div class="card table-card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-info bg-opacity-10 p-3">
                            <i class="bi bi-book fs-3 text-info"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Books</div>
                            <small class="text-muted">Manage books</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('borrows.index') }}" class="text-decoration-none">
                <div class="card table-card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-arrow-left-right fs-3 text-warning"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark">Borrows</div>
                            <small class="text-muted">Borrow & return</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
