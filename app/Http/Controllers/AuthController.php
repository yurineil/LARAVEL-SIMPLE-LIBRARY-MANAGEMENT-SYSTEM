<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = strtolower(trim($request->email));
        $password = trim($request->password);

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if ($user && Hash::check($password, $user->password)) {
            if ($user->isAdmin() && !$user->isApproved()) {
                return back()->withErrors(['email' => 'Your admin account is pending approval.']);
            }
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            if ($user->isAdmin()) {
                return redirect()->intended(route('dashboard'));
            }
            return redirect()->intended(route('student.home'));
        }

        if ($user) {
            return back()->withErrors(['password' => 'Invalid password.'])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,student',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $role = $validated['role'];
        $email = strtolower(trim($validated['email']));
        $password = trim($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
            'approved_at' => $role === 'student' ? now() : null,
        ]);

        if ($role === 'student') {
            Student::updateOrCreate(
                ['email' => $user->email],
                ['name' => $user->name, 'student_id' => 'U' . $user->id]
            );
            Auth::login($user);
            return redirect()->intended(route('student.home'));
        }

        return redirect()->route('login')->with('success', 'Admin registration submitted. An existing admin must approve your account before you can log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
