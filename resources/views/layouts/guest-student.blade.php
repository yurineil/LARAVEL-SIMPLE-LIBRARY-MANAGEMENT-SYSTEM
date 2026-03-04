<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student — Library System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh; }
        .navbar-guest-student { background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #3a7bd5 100%); padding: 0.6rem 1.5rem; }
        .navbar-guest-student .navbar-brand { font-weight: 700; color: #fff; }
        .navbar-guest-student .nav-link { color: rgba(255,255,255,0.9); font-weight: 500; }
        .main-guest { max-width: 900px; margin: 0 auto; padding: 2rem 1rem; }
        .table-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); overflow: hidden; }
        .table thead th { background: #f8f9fa; font-weight: 600; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-guest-student">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('guest.borrows') }}"><i class="bi bi-book-half me-1"></i>Library MS</a>
            <span class="navbar-text text-white me-3"><i class="bi bi-person-badge me-1"></i>{{ $student->name }} ({{ $student->student_id }})</span>
            <form method="POST" action="{{ route('guest.student.end') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">End session</button>
            </form>
        </div>
    </nav>
    <main class="main-guest">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
