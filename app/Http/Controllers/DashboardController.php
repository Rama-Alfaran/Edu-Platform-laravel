<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::guard('teacher')->check()) {
            $role = 'Teacher';
            $user = Auth::guard('teacher')->user();
            return view('dashboard', compact('user', 'role'));
        } elseif (Auth::guard('student')->check()) {
            $role = 'Student';
            $user = Auth::guard('student')->user();
            return view('student-dashboard', compact('user', 'role'));
        } else {
            return redirect('/login');
        }
    }
}