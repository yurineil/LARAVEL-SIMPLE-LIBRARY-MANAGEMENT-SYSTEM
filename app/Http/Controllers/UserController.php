<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);

        return view('users.index', compact('users'));
    }

    public function impersonate(Request $request, User $user)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Only admins can impersonate users.');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot impersonate another admin.');
        }

        session(['impersonating_from' => $request->user()->id]);
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "Now viewing as {$user->name}.");
    }

    public function stopImpersonate(Request $request)
    {
        $adminId = session('impersonating_from');
        if (!$adminId) {
            return redirect()->route('dashboard');
        }

        session()->forget('impersonating_from');
        Auth::login(User::findOrFail($adminId));

        return redirect()->route('users.index')->with('success', 'Returned to admin view.');
    }

    public function approve(User $user)
    {
        if (!$user->isAdmin() || $user->isApproved()) {
            return back()->with('error', 'User is not a pending admin.');
        }
        $user->update(['approved_at' => now()]);
        return back()->with('success', "{$user->name} has been approved and can now log in.");
    }
}
