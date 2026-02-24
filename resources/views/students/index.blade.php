@extends('layouts.app')

@section('title', 'Students — Library System')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2><i class="bi bi-person-vcard me-2"></i>Students</h2>
        <p class="text-muted mb-0">Manage students (no login required for students)</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Student
    </a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group" style="max-width: 320px;">
        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Search by name, email, ID...">
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
                        <th>Email</th>
                        <th>Student ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="fw-semibold text-muted">{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                        <td class="fw-semibold">{{ $student->name }}</td>
                        <td>{{ $student->email ?? '—' }}</td>
                        <td>{{ $student->student_id ?? '—' }}</td>
                        <td>
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('students.destroy', $student) }}" class="d-inline" onsubmit="return confirm('Delete this student?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No students found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $students->links('pagination::bootstrap-5') }}
</div>
@endsection
