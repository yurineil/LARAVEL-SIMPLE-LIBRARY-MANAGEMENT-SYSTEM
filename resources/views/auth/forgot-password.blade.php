<x-guest-layout>
    <p class="text-muted small mb-4">
        Forgot your password? Enter your email and we'll send you a link to reset it.
    </p>

    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            @foreach ($errors->get('email') as $message)
                {{ $message }}
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus placeholder="your@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <a class="text-decoration-none text-secondary small" href="{{ route('login') }}">Back to login</a>
            <button type="submit" class="btn btn-primary btn-login px-4">
                <i class="bi bi-envelope me-2"></i>Email Password Reset Link
            </button>
        </div>
    </form>
</x-guest-layout>
