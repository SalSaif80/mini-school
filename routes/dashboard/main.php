<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

Route::middleware('auth')->prefix('dashboard-school')->group(function () {
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard-school');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User management routes (Admin only)
    //
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);




    // Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-verification', [UserController::class, 'toggleVerification'])->name('users.toggle-verification');


    // Course management routes
    Route::resource('courses', CourseController::class);
    Route::get('courses/{course}/students', [CourseController::class, 'students'])->name('courses.students');

    // Student management routes
    Route::resource('students', StudentController::class);

    // Teacher management routes
    Route::resource('teachers', TeacherController::class);

    // Enrollment management routes
    Route::resource('enrollments', EnrollmentController::class);
    Route::patch('enrollments/{enrollment}/mark-completed', [EnrollmentController::class, 'markCompleted'])->name('enrollments.markCompleted');
    Route::patch('enrollments/{enrollment}/mark-dropped', [EnrollmentController::class, 'markDropped'])->name('enrollments.markDropped');
});
