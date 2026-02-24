<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Name</label>
            <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label fw-semibold">Role</label>
            <select id="role" name="role" class="form-select form-control-lg @error('role') is-invalid @enderror" required>
                <option value="student" {{ old('role', 'student') === 'student' ? 'selected' : '' }}>Student</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <small class="text-muted">Admin accounts require approval by an existing admin.</small>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
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
            <a class="text-decoration-none text-secondary small" href="{{ route('login') }}">Already registered?</a>
            <button type="submit" class="btn btn-primary btn-login px-4">
                <i class="bi bi-person-plus me-2"></i>Register
            </button>
        </div>
    </form>
</x-guest-layout>
