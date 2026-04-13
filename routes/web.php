<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MyCoursesController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
})->name('register.show');

Route::post('/register', [RegisterController::class, 'store'])->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Routes for both teachers and students
Route::middleware('auth:teacher,student')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chat', [ChatController::class, 'index']);
    Route::post('/chat/start', [ChatController::class, 'startConversation']);
    Route::get('/chat/{conversationId}', [ChatController::class, 'show']);
    Route::post('/chat/{conversationId}/send', [ChatController::class, 'sendMessage']);
    Route::get('/chat/{conversationId}/messages/{lastMessageId}', [ChatController::class, 'getNewMessages']);
});

// Teacher only routes
Route::middleware('auth:teacher')->group(function () {
    // Student Management
    Route::get('/students', [StudentManagementController::class, 'index']);
    Route::get('/add_students', [StudentController::class, 'create']);
    Route::post('/add_students', [StudentController::class, 'store']);
    Route::get('/students/{id}/edit', [StudentController::class, 'edit']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}/delete', [StudentController::class, 'destroy']);
    
    // Course Management
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/add_courses', [CourseController::class, 'create']);
    Route::post('/add_courses', [CourseController::class, 'store']);
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}/delete', [CourseController::class, 'destroy']);
});

// Student only routes
Route::middleware('auth:student')->group(function () {
    Route::get('/my_courses', [MyCoursesController::class, 'index']);
});