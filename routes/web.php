<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentBorrowController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Fix admin login when you get "credentials do not match" (only when APP_DEBUG=true)
Route::get('/fix-admin-login', function () {
    if (! config('app.debug')) {
        abort(404);
    }
    User::updateOrCreate(
        ['email' => 'admin@library.com'],
        ['name' => 'Librarian', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'admin', 'approved_at' => now()]
    );
    return redirect()->route('login')->with('success', 'Admin account reset. Log in with: admin@library.com / password');
})->name('fix-admin-login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Student home and borrow (students can borrow books and see their borrows)
    Route::get('/student', fn () => view('student.home'))->name('student.home');
    Route::get('/student/books', [StudentBorrowController::class, 'index'])->name('student.books');
    Route::get('/student/borrow', [StudentBorrowController::class, 'create'])->name('student.borrow.create');
    Route::post('/student/borrow', [StudentBorrowController::class, 'store'])->name('student.borrow.store');
    Route::get('/student/borrow/add/{book}', [StudentBorrowController::class, 'addToCart'])->name('student.borrow.add');
    Route::get('/student/borrow/remove/{book}', [StudentBorrowController::class, 'removeFromCart'])->name('student.borrow.remove');
    Route::get('/student/my-borrows', [StudentBorrowController::class, 'myBorrows'])->name('student.my-borrows');
    Route::get('/student/my-borrows/{borrow}/return', [StudentBorrowController::class, 'returnForm'])->name('student.return.form');
    Route::post('/student/return', [StudentBorrowController::class, 'processReturn'])->name('student.return.process');

    // Change password (all logged-in users: admins and students)
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Admin-only routes (students get 403 if they try to open these)
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
        Route::post('/users/stop-impersonate', [UserController::class, 'stopImpersonate'])->name('users.stop-impersonate');
        Route::resource('students', StudentController::class);
        Route::resource('authors', AuthorController::class);
        Route::resource('books', BookController::class);
        Route::get('/borrows', [BorrowController::class, 'index'])->name('borrows.index');
        Route::get('/borrows/create', [BorrowController::class, 'create'])->name('borrows.create');
        Route::post('/borrows', [BorrowController::class, 'store'])->name('borrows.store');
        Route::get('/borrows/{borrow}/return', [BorrowController::class, 'returnForm'])->name('borrows.return');
        Route::post('/borrows/return', [BorrowController::class, 'processReturn'])->name('borrows.process-return');
    });
});
