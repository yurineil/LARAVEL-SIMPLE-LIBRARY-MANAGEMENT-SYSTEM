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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if ($user->isAdmin() && !$user->isApproved()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Your admin account is pending approval.']);
            }
            $request->session()->regenerate();
            if ($user->isAdmin()) {
                return redirect()->intended(route('dashboard'));
            }
            return redirect()->intended(route('student.home'));
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
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
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
