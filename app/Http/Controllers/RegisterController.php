<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Teacher;
use App\Models\Student;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $role = $request->role;
        $model = ($role == 'Teacher') ? Teacher::class : Student::class;

        // Validation
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email|unique:students,email',
            'password' => 'required|confirmed|min:8',
            'gender' => 'required|in:Male,Female',
            'hobbies' => 'array',
            'birthdate' => 'required|date',
            'role' => 'required|in:Teacher,Student',
        ]);

        // Age validation (adjusted to current date October 04, 2025, 10:17 PM +03)
        $birthdate = Carbon::parse($validated['birthdate']);
        $now = Carbon::now(); // Current date: October 04, 2025, 10:17 PM +03
        $age = $now->diffInYears($birthdate);
        $minAge = $role == 'Student' ? 5 : 18;
        $maxAge = $role == 'Student' ? 25 : 65;

        if ($birthdate->gt($now->subYears($minAge)) || $birthdate->lt($now->subYears($maxAge)->addYear())) {
            return back()->withErrors(['birthdate' => ucfirst($role) . 's must be between ' . $minAge . ' and ' . $maxAge . ' years old.']);
        }

        $hobbies = implode(',', $validated['hobbies'] ?? []);

        $user = $model::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'],
            'hobbies' => $hobbies,
            'birthdate' => $validated['birthdate'],
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }
}