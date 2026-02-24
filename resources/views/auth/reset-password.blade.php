<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                   value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">New Password</label>
            <input type="password" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                   required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" required autocomplete="new-password">
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <a class="text-decoration-none text-secondary small" href="{{ route('login') }}">Back to login</a>
            <button type="submit" class="btn btn-primary btn-login px-4">
                <i class="bi bi-key me-2"></i>Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>
