<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        $search = $request->query('search');

        $enrollments = Enrollment::with(['student', 'course'])
            ->whereHas('course', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('student', function ($q) use ($search) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search%"]);
                })->orWhereHas('course', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->get();

        return view('students', compact('enrollments', 'search'));
    }
}