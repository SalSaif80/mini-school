<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Student\StudentController;
use App\Helpers\UserRedirectHelper;

Route::get('/', function () {
    return redirect('/login');
});



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard route (redirects based on user type)
Route::get('/dashboard-mini-school', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {


    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard')->middleware(['auth', '2fa']);

    // User management routes
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');

    // Courses management routes
    Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
    Route::get('/courses/create', [AdminController::class, 'createCourse'])->name('courses.create');
    Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');
    Route::get('/courses/{id}', [AdminController::class, 'showCourse'])->name('courses.show');
    Route::get('/courses/{id}/edit', [AdminController::class, 'editCourse'])->name('courses.edit');
    Route::put('/courses/{id}', [AdminController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{id}', [AdminController::class, 'deleteCourse'])->name('courses.destroy');
    Route::get('/courses/{id}/students', [AdminController::class, 'courseStudents'])->name('courses.students');

    // Enrollments management routes
    Route::get('/enrollments', [AdminController::class, 'enrollments'])->name('enrollments');
    Route::post('/enrollments', [AdminController::class, 'storeEnrollment'])->name('enrollments.store');
    Route::get('/enrollments/{id}', [AdminController::class, 'showEnrollment'])->name('enrollments.show');
    Route::get('/enrollments/{id}/edit', [AdminController::class, 'editEnrollment'])->name('enrollments.edit');
    Route::put('/enrollments/{id}', [AdminController::class, 'updateEnrollment'])->name('enrollments.update');
    Route::delete('/enrollments/{id}', [AdminController::class, 'deleteEnrollment'])->name('enrollments.destroy');

    Route::get('/activity-log', [AdminController::class, 'activityLog'])->name('activity-log');

    // Redirect projects to courses (since projects don't exist)
    Route::get('/projects', function() {
        return redirect()->route('admin.courses')->with('info', 'تم توجيهك إلى إدارة الكورسات');
    });
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [TeacherController::class, 'courses'])->name('courses');
    Route::get('/courses/{course}/students', [TeacherController::class, 'courseStudents'])->name('course.students');
    Route::patch('/enrollments/{enrollment}/grade', [TeacherController::class, 'updateGrade'])->name('update.grade');
    Route::get('/enrollments/{enrollment}/exam-file/download', [TeacherController::class, 'downloadExamFile'])->name('download.exam');
    Route::get('/enrollments/{enrollment}/exam-file/view', [TeacherController::class, 'viewExamFile'])->name('view.exam');
});

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses', [StudentController::class, 'courses'])->name('courses');
    Route::get('/available-courses', [StudentController::class, 'availableCourses'])->name('available.courses');
    Route::post('/enroll/{course}', [StudentController::class, 'enrollInCourse'])->name('enroll');
    Route::patch('/withdraw/{enrollment}', [StudentController::class, 'withdrawFromCourse'])->name('withdraw');
    Route::get('/grades', [StudentController::class, 'grades'])->name('grades');
    Route::post('/enrollments/{enrollment}/upload-exam', [StudentController::class, 'uploadExamFile'])->name('upload.exam');
    Route::delete('/enrollments/{enrollment}/delete-exam', [StudentController::class, 'deleteExamFile'])->name('delete.exam');
});
