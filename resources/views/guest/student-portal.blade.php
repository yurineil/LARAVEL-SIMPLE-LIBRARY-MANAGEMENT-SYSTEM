<x-guest-layout>
    <div class="text-center mb-3">
        <h5 class="fw-bold text-dark">Student portal</h5>
        <p class="text-muted small mb-0">No login required. Enter your Student ID to borrow or return books.</p>
    </div>
    @if (session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('guest.student.identify') }}">
        @csrf
        <div class="mb-3">
            <label for="student_id" class="form-label fw-semibold">Student ID</label>
            <input type="text" id="student_id" name="student_id" class="form-control form-control-lg @error('student_id') is-invalid @enderror"
                   value="{{ old('student_id') }}" required autofocus placeholder="e.g. STU001 or U1">
            @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="text-center mb-3">
            <button type="submit" class="btn btn-primary btn-login px-4 w-100"><i class="bi bi-box-arrow-in-right me-2"></i>Continue</button>
        </div>
    </form>
    <p class="text-center text-muted small mb-0 mt-3">
        Staff or have an account? <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Log in</a>
    </p>
</x-guest-layout>
