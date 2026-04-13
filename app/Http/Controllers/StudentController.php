<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;

class StudentController extends Controller
{
    public function create()
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        $courses = Course::where('teacher_id', $teacher->id)
            ->where('is_deleted', 0)
            ->get();

        return view('add-student', compact('courses'));
    }

    public function store(Request $request)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'course_id' => 'nullable|exists:courses,id',
            'mark' => 'nullable|integer|min:0|max:1000',
        ]);

        // Check if student already exists by email
        $student = Student::where('email', $validated['email'])->first();

        // If student doesn't exist, create new one
        if (!$student) {
            $student = Student::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'gender' => 'Male', // Default
                'hobbies' => '', // Default empty
                'birthdate' => now()->subYears(18), // Default 18 years old
            ]);
        }

        // Enroll in course if provided
        if ($request->course_id) {
            // Check if already enrolled in this course
            $existingEnrollment = Enrollment::where('student_id', $student->id)
                ->where('course_id', $request->course_id)
                ->first();

            if ($existingEnrollment) {
                return redirect('/students')->with('error', 'Student is already enrolled in this course!');
            }

            Enrollment::create([
                'student_id' => $student->id,
                'course_id' => $request->course_id,
                'mark' => $request->mark,
            ]);
        }

        return redirect('/students')->with('success', 'Student enrolled successfully!');
    }

    public function edit($id)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        
        // Get enrollment with student and course
        $enrollment = Enrollment::with(['student', 'course'])
            ->whereHas('course', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->where('id', $id)
            ->firstOrFail();

        $courses = Course::where('teacher_id', $teacher->id)
            ->where('is_deleted', 0)
            ->get();

        return view('edit-student', compact('enrollment', 'courses'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        
        $enrollment = Enrollment::with(['student', 'course'])
            ->whereHas('course', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'mark' => 'nullable|integer|min:0|max:1000',
            'course_id' => 'required|exists:courses,id',
        ]);

        $enrollment->mark = $validated['mark'];
        $enrollment->course_id = $validated['course_id'];
        $enrollment->save();

        return redirect('/students')->with('success', 'Student enrollment updated successfully!');
    }

    public function destroy($id)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        
        // Find the enrollment and verify it belongs to this teacher's course
        $enrollment = Enrollment::with(['course'])
            ->whereHas('course', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->where('id', $id)
            ->firstOrFail();

        // Delete the enrollment
        $enrollment->delete();

        return redirect('/students')->with('success', 'Student enrollment deleted successfully!');
    }
    public function destroy($id)
{
    if (!Auth::guard('teacher')->check()) {
        return redirect('/login');
    }

    $teacher = Auth::guard('teacher')->user();
    
    // Find the enrollment and verify it belongs to this teacher's course
    $enrollment = Enrollment::with(['course'])
        ->whereHas('course', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
        ->where('id', $id)
        ->firstOrFail();

    // Delete the enrollment
    $enrollment->delete();

    return redirect('/students')->with('success', 'Student enrollment deleted successfully!');
}
}