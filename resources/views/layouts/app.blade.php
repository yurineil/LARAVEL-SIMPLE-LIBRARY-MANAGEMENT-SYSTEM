<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Library System'))</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-gradient: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #3a7bd5 100%);
            --accent-color: #3a7bd5;
            --dark-bg: #0f1a2e;
            --card-bg: #1a2940;
        }

        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .navbar-custom {
            background: var(--primary-gradient) !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
            padding: 0.8rem 1.5rem;
        }
        .navbar-custom .navbar-brand { font-weight: 700; font-size: 1.4rem; }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.15);
        }

        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - 70px);
            background: linear-gradient(180deg, #0f1a2e 0%, #1a2940 100%);
            padding: 1.5rem 0;
            overflow-y: auto;
            z-index: 100;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.75rem 1.5rem;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
            border-left-color: var(--accent-color);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(58,123,213,0.2);
            border-left-color: var(--accent-color);
        }
        .sidebar-heading {
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 1rem 1.5rem 0.5rem;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: calc(100vh - 70px);
        }
        .main-content.no-sidebar { margin-left: 0; }

        .table-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .table thead th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.85rem;
            color: #495057;
            padding: 1rem;
        }
        .table tbody td { padding: 1rem; vertical-align: middle; }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            font-weight: 600;
            border-radius: 10px;
        }
        .btn-primary:hover {
            filter: brightness(1.05);
            box-shadow: 0 4px 15px rgba(58,123,213,0.4);
        }

        .page-header { margin-bottom: 2rem; }
        .page-header h2 { font-weight: 700; color: #1a2940; }

        .alert { border: none; border-radius: 12px; }

        /* Status badges: always visible, not same as background; hover for clarity */
        .badge-status {
            font-weight: 600;
            padding: 0.4rem 0.75rem;
            border-radius: 9999px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .badge-status:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .badge-status.badge-returned {
            background-color: #198754 !important;
            color: #fff !important;
        }
        .badge-status.badge-borrowed {
            background-color: #fd7e14 !important;
            color: #fff !important;
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container-fluid">
            @auth
            <button class="btn d-lg-none me-2 text-white" type="button" onclick="document.getElementById('sidebar').classList.toggle('show')" aria-label="Toggle menu">
                <i class="bi bi-list fs-4"></i>
            </button>
            @endauth
            <a class="navbar-brand" href="{{ Auth::user()->isAdmin() ? route('dashboard') : route('student.home') }}">
                <i class="bi bi-book-half me-1"></i>LibraryMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-decoration-none text-white">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
                @endauth
            </div>
        </div>
    </nav>

    <div style="margin-top: 70px;">
        @auth
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-heading">{{ Auth::user()->isAdmin() ? 'Admin' : 'Menu' }}</div>
            <nav class="nav flex-column">
                @if(Auth::user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                        <i class="bi bi-person-vcard"></i> Students
                    </a>
                    <a class="nav-link {{ request()->routeIs('authors.*') ? 'active' : '' }}" href="{{ route('authors.index') }}">
                        <i class="bi bi-pencil-square"></i> Authors
                    </a>
                    <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">
                        <i class="bi bi-book"></i> Books
                    </a>
                    <a class="nav-link {{ request()->routeIs('borrows.*') ? 'active' : '' }}" href="{{ route('borrows.index') }}">
                        <i class="bi bi-arrow-left-right"></i> Borrows
                    </a>
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                @else
                    <a class="nav-link {{ request()->routeIs('student.home') ? 'active' : '' }}" href="{{ route('student.home') }}">
                        <i class="bi bi-person-badge"></i> Student
                    </a>
                    <a class="nav-link {{ request()->routeIs('student.books') ? 'active' : '' }}" href="{{ route('student.books') }}">
                        <i class="bi bi-book"></i> Browse Books
                    </a>
                    <a class="nav-link {{ request()->routeIs('student.borrow.*') ? 'active' : '' }}" href="{{ route('student.borrow.create') }}">
                        <i class="bi bi-bookmark-plus"></i> Borrow Books
                    </a>
                    <a class="nav-link {{ request()->routeIs('student.my-borrows') ? 'active' : '' }}" href="{{ route('student.my-borrows') }}">
                        <i class="bi bi-journal-bookmark"></i> My Borrows
                    </a>
                @endif
                <a class="nav-link {{ request()->routeIs('password.*') ? 'active' : '' }}" href="{{ route('password.edit') }}">
                    <i class="bi bi-key"></i> Change Password
                </a>
            </nav>
        </aside>
        <main class="main-content">
        @else
        <main class="main-content no-sidebar">
        @endauth
            @isset($header)
                <header class="mb-4">
                    <div class="d-flex align-items-center">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>
