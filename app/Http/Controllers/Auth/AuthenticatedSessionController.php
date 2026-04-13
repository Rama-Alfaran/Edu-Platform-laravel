<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Try teacher login
        if (Auth::guard('teacher')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        // Try student login
        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard'); // Student also goes to dashboard first
        }

        // Login failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function destroy(Request $request)
    {
        // Determine which guard to logout
        if (Auth::guard('teacher')->check()) {
            Auth::guard('teacher')->logout();
        } elseif (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}