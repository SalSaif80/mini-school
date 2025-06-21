<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

// Custom Login Route
Route::post('/custom-login', [App\Http\Controllers\Auth\CustomLoginController::class, 'login'])->name('custom.login');

Route::middleware('auth')->prefix('dashboard-school')->group(function () {
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard-school');

    // Admin Dashboard
    Route::middleware('user.type:admin')->prefix('admin')->group(function () {
        Route::get('/', function () {
            return view('dashboard.admin.index');
        })->name('admin.dashboard');
    });

    // Teacher Dashboard
    Route::middleware('user.type:teacher')->prefix('teacher')->group(function () {
        Route::get('/', function () {
            return view('dashboard.teacher.index');
        })->name('teacher.dashboard');
    });

    // Student Dashboard
    Route::middleware('user.type:student')->prefix('student')->group(function () {
        Route::get('/', function () {
            return view('dashboard.student.index');
        })->name('student.dashboard');
    });

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

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/

// Admin Routes - Full system access
Route::middleware(['auth', 'user.type:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.admin.index');
    })->name('dashboard');

    // User Management
    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class)->except(['dashboard', 'myCourses', 'myGrades', 'availableCourses', 'registerCourse', 'profile', 'updateProfile', 'withdrawCourse']);
    Route::resource('teachers', TeacherController::class)->except(['dashboard', 'myCourses', 'myStudents', 'courseStudents', 'profile', 'updateProfile', 'updateGrade', 'browseCourses']);
    Route::resource('roles', RoleController::class);

    // Course Management
    Route::resource('courses', CourseController::class);
    Route::resource('enrollments', EnrollmentController::class);
});

// Teacher Routes - Teaching specific access
Route::middleware(['auth', 'user.type:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

    // My Teaching
    Route::get('/my-courses', [TeacherController::class, 'myCourses'])->name('my-courses');
    Route::get('/my-students', [TeacherController::class, 'myStudents'])->name('my-students');
    Route::get('/course/{course}/students', [TeacherController::class, 'courseStudents'])->name('course.students');

    // Profile Management
    Route::get('/profile', [TeacherController::class, 'profile'])->name('profile');
    Route::patch('/profile', [TeacherController::class, 'updateProfile'])->name('profile.update');

    // Grade Management
    Route::patch('/enrollment/{enrollment}/grade', [TeacherController::class, 'updateGrade'])->name('enrollment.grade');

    // Browse Courses (Read-only)
    Route::get('/browse-courses', [TeacherController::class, 'browseCourses'])->name('browse-courses');
});

// Student Routes - Personal academic access
Route::middleware(['auth', 'user.type:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard-school', [StudentController::class, 'dashboard'])->name('dashboard');

    // My Studies
    Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('my-courses');
    Route::get('/my-grades', [StudentController::class, 'myGrades'])->name('my-grades');

    // Course Registration
    Route::get('/available-courses', [StudentController::class, 'availableCourses'])->name('available-courses');
    Route::post('/register-course/{course}', [StudentController::class, 'registerCourse'])->name('register-course');
    Route::patch('/withdraw-course/{enrollment}', [StudentController::class, 'withdrawCourse'])->name('withdraw-course');

    // Profile Management
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::patch('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
});

// Shared Routes (with appropriate access control)
Route::middleware(['auth'])->group(function () {
    // Course viewing (all users can view courses)
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    // Enrollment viewing (restricted to own enrollments or teaching courses)
    Route::get('/enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');
});

// Legacy Routes Redirect (for backward compatibility)
Route::middleware(['auth'])->group(function () {
    // Redirect old routes to new organized structure
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->user_type === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->user_type === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect('/');
    })->name('dashboard');
});
