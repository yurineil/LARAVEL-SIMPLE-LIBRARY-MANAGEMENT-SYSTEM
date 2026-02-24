<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login — ' . config('app.name', 'Library System'))</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #3a7bd5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .guest-card {
            max-width: 420px;
            width: 100%;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .guest-logo {
            font-size: 2rem;
            font-weight: 700;
            color: #1e3a5f;
        }
        .guest-logo i { color: #3a7bd5; }
        .btn-login {
            background: linear-gradient(135deg, #1e3a5f 0%, #3a7bd5 100%);
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
        }
        .btn-login:hover { filter: brightness(1.08); color: #fff; }
        .placeholder-small::placeholder { font-size: 0.8rem; opacity: 0.85; }
    </style>
</head>
<body>
    <div class="guest-card card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <span class="guest-logo"><i class="bi bi-book-half me-2"></i>Library MS</span>
            </div>
            {{ $slot }}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
