<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        $search = $request->query('search');

        $courses = Course::where('teacher_id', $teacher->id)
            ->where('is_deleted', 0)
            ->when($search, function ($q) use ($search) {
                $q->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('description', 'like', "%$search%");
                });
            })
            ->get();

        return view('courses', compact('courses', 'search'));
    }

    public function create()
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        return view('add-course');
    }

    public function store(Request $request)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_mark' => 'required|integer|min:1|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teacher = Auth::guard('teacher')->user();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'base_mark' => $validated['base_mark'],
            'image' => $imagePath,
            'teacher_id' => $teacher->id,
            'is_deleted' => 0,
        ]);

        return redirect('/courses')->with('success', 'Course added successfully!');
    }

    public function edit($id)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        $course = Course::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->where('is_deleted', 0)
            ->firstOrFail();

        return view('edit-course', compact('course'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        $course = Course::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->where('is_deleted', 0)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_mark' => 'required|integer|min:1|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $course->image = $request->file('image')->store('courses', 'public');
        }

        $course->name = $validated['name'];
        $course->description = $validated['description'];
        $course->base_mark = $validated['base_mark'];
        $course->save();

        return redirect('/courses')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        if (!Auth::guard('teacher')->check()) {
            return redirect('/login');
        }

        $teacher = Auth::guard('teacher')->user();
        $course = Course::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $course->is_deleted = 1;
        $course->save();

        return redirect('/courses')->with('success', 'Course deleted successfully!');
    }
}