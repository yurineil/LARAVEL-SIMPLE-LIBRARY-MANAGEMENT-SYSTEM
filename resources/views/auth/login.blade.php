<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-info mb-3">{{ session('status') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" id="email" name="email" class="form-control form-control-lg placeholder-small @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="your@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input type="password" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                   required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
        </div>

        <div class="text-center mb-3">
            <button type="submit" class="btn btn-primary btn-login px-4">
                <i class="bi bi-box-arrow-in-right me-2"></i>Log in
            </button>
        </div>
        @if (Route::has('password.request'))
            <p class="text-center mb-3">
                <a class="text-decoration-none text-secondary small" href="{{ route('password.request') }}">Forgot password?</a>
            </p>
        @endif
        @if (Route::has('register'))
            <p class="text-center text-muted small mb-0">
                Don't have an account? <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Register</a>
            </p>
        @endif
    </form>
</x-guest-layout>
