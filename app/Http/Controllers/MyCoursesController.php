<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class MyCoursesController extends Controller
{
    public function index()
    {
        if (!Auth::guard('student')->check()) {
            return redirect('/login');
        }

        $student = Auth::guard('student')->user();
        
        $enrollments = Enrollment::with(['course'])
            ->where('student_id', $student->id)
            ->get();

        return view('my-courses', compact('enrollments'));
    }
}