<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
        ];

        $recent_activities = Activity::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_activities'));
    }

    // ===== إدارة المستخدمين =====
    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'password' => 'required|string|min:6|confirmed',
            'user_type' => 'required|in:admin,teacher,student',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        // تعيين الـ role المناسب
        $user->assignRole($request->user_type);

        // تسجيل النشاط
        activity()
            ->causedBy(Auth::user())
            ->log('تم إنشاء مستخدم جديد: ' . $user->name);

        return redirect()->route('admin.users')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);

        // إحصائيات المستخدم
        $stats = [];
        if ($user->user_type === 'teacher') {
            $stats['courses'] = Course::where('teacher_id', $user->id)->count();
            $stats['students'] = Enrollment::whereHas('course', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })->distinct('student_id')->count();
        } elseif ($user->user_type === 'student') {
            $stats['enrollments'] = Enrollment::where('student_id', $user->id)->count();
            $stats['completed'] = Enrollment::where('student_id', $user->id)
                ->where('status', 'completed')->count();
        }

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'user_type' => 'required|in:admin,teacher,student',
        ]);

        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'user_type' => $request->user_type,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // تحديث الـ role إذا تغير user_type
        $user->syncRoles([$request->user_type]);

        // تسجيل النشاط
        activity()
            ->causedBy(Auth::user())
            ->log('تم تحديث بيانات المستخدم: ' . $user->name);

        return redirect()->route('admin.users')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // منع حذف المستخدم الحالي
        if ($user->id === Auth::user()->id) {
            return redirect()->route('admin.users')
                ->with('error', 'لا يمكنك حذف حسابك الشخصي');
        }

        $userName = $user->name;
        $user->delete();

        // تسجيل النشاط
        activity()
            ->causedBy(Auth::user())
            ->log('تم حذف المستخدم: ' . $userName);

        return redirect()->route('admin.users')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    // ===== إدارة الكورسات =====
    public function courses()
    {
        $courses = Course::with('teacher')
            ->withCount('enrollments')
            ->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    public function createCourse()
    {
        $teachers = User::where('user_type', 'teacher')->get();
        return view('admin.courses.create', compact('teachers'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'room_number' => 'required|string|max:50',
        ]);

        $course = Course::create([
            'course_name' => $request->course_name,
            'teacher_id' => $request->teacher_id,
            'schedule_date' => $request->schedule_date,
            'room_number' => $request->room_number,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->log('تم إنشاء كورس جديد: ' . $course->course_name);

        return redirect()->route('admin.courses')
            ->with('success', 'تم إنشاء الكورس بنجاح');
    }

    public function showCourse($id)
    {
        $course = Course::with(['teacher', 'enrollments.student'])
            ->withCount('enrollments')
            ->findOrFail($id);

        $stats = [
            'total_students' => $course->enrollments->count(),
            'active_students' => $course->enrollments->where('status', 'active')->count(),
            'completed_students' => $course->enrollments->where('status', 'completed')->count(),
            'dropped_students' => $course->enrollments->where('status', 'dropped')->count(),
        ];

        return view('admin.courses.show', compact('course', 'stats'));
    }

    public function editCourse($id)
    {
        $course = Course::findOrFail($id);
        $teachers = User::where('user_type', 'teacher')->get();
        return view('admin.courses.edit', compact('course', 'teachers'));
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'course_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'room_number' => 'required|string|max:50',
        ]);

        $course->update([
            'course_name' => $request->course_name,
            'teacher_id' => $request->teacher_id,
            'schedule_date' => $request->schedule_date,
            'room_number' => $request->room_number,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->log('تم تحديث الكورس: ' . $course->course_name);

        return redirect()->route('admin.courses')
            ->with('success', 'تم تحديث الكورس بنجاح');
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $courseName = $course->course_name;

        // حذف التسجيلات المرتبطة
        $course->enrollments()->delete();
        $course->delete();

        activity()
            ->causedBy(Auth::user())
            ->log('تم حذف الكورس: ' . $courseName);

        return redirect()->route('admin.courses')
            ->with('success', 'تم حذف الكورس بنجاح');
    }

    public function courseStudents($id)
    {
        $course = Course::with(['teacher', 'enrollments.student'])
            ->findOrFail($id);

        return view('admin.courses.students', compact('course'));
    }

    public function enrollments(Request $request)
    {
        $query = Enrollment::with(['student', 'course.teacher']);

        // فلترة حسب الكورس
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الفصل الدراسي
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // البحث في اسم الطالب
        if ($request->filled('search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $enrollments = $query->orderBy('created_at', 'desc')->paginate(15);

        // إحصائيات
        $stats = [
            'total' => Enrollment::count(),
            'active' => Enrollment::where('status', 'active')->count(),
            'completed' => Enrollment::where('status', 'completed')->count(),
            'failed' => Enrollment::where('status', 'failed')->count(),
            'dropped' => Enrollment::where('status', 'dropped')->count(),
        ];

        // بيانات إضافية للفلاتر
        $courses = Course::with('teacher')->orderBy('course_name')->get();
        $students = User::where('user_type', 'student')->orderBy('name')->get();
        $semesters = Enrollment::distinct()->pluck('semester')->filter()->sort()->values();

        return view('admin.enrollments.index', compact('enrollments', 'stats', 'courses', 'students', 'semesters'));
    }

    public function storeEnrollment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,course_id',
            'semester' => 'required|string|max:50',
        ]);

        // التحقق من عدم وجود تسجيل مسبق
        $existingEnrollment = Enrollment::where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($existingEnrollment) {
            return back()->with('error', 'الطالب مسجل في هذا الكورس مسبقاً');
        }

        $student = User::findOrFail($request->student_id);
        $course = Course::findOrFail($request->course_id);

        $enrollment = Enrollment::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'enrollment_date' => now(),
            'semester' => $request->semester,
            'status' => 'active',
        ]);

        activity()
            ->causedBy(Auth::user())
            ->log("تم تسجيل الطالب {$student->name} في كورس {$course->course_name}");

        return redirect()->route('admin.enrollments')
            ->with('success', "تم تسجيل الطالب {$student->name} في كورس {$course->course_name} بنجاح");
    }

    public function showEnrollment($id)
    {
        $enrollment = Enrollment::with([
            'student.enrollments',
            'course.teacher',
            'course.enrollments'
        ])->findOrFail($id);

        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function editEnrollment($id)
    {
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($id);
        $courses = Course::with('teacher')->orderBy('course_name')->get();
        $students = User::where('user_type', 'student')->orderBy('name')->get();

        return view('admin.enrollments.edit', compact('enrollment', 'courses', 'students'));
    }

    public function updateEnrollment(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,course_id',
            'semester' => 'required|string|max:50',
            'status' => 'required|in:active,completed,failed,dropped',
            'final_exam_grade' => 'nullable|numeric|min:0|max:100',
        ]);

        // التحقق من عدم وجود تسجيل مكرر (إذا تم تغيير الطالب أو الكورس)
        if ($request->student_id != $enrollment->student_id || $request->course_id != $enrollment->course_id) {
            $existingEnrollment = Enrollment::where('student_id', $request->student_id)
                ->where('course_id', $request->course_id)
                ->where('enrollment_id', '!=', $id)
                ->first();

            if ($existingEnrollment) {
                return back()->with('error', 'الطالب مسجل في هذا الكورس مسبقاً');
            }
        }

        $oldData = [
            'student' => $enrollment->student->name,
            'course' => $enrollment->course->course_name,
            'status' => $enrollment->status,
            'grade' => $enrollment->final_exam_grade
        ];

        $enrollment->update([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'semester' => $request->semester,
            'status' => $request->status,
            'final_exam_grade' => $request->final_exam_grade,
        ]);

        // تحديث الدرجة الحرفية والحالة إذا تم إدخال درجة
        if ($request->filled('final_exam_grade')) {
            $enrollment->updateGradeAndStatus();
        }

        $newStudent = User::find($request->student_id);
        $newCourse = Course::find($request->course_id);

        activity()
            ->causedBy(Auth::user())
            ->log("تم تحديث تسجيل الطالب {$newStudent->name} في كورس {$newCourse->course_name}");

        return redirect()->route('admin.enrollments')
            ->with('success', 'تم تحديث التسجيل بنجاح');
    }

    public function deleteEnrollment($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $userName = $enrollment->student->name;
        $courseName = $enrollment->course->course_name;
        $enrollment->delete();

        activity()
            ->causedBy(Auth::user())
            ->log("تم حذف تسجيل الطالب {$userName} من كورس {$courseName}");

        return redirect()->route('admin.enrollments')
            ->with('success', 'تم حذف التسجيل بنجاح');
    }

    public function activityLog()
    {
        $activities = Activity::latest()->paginate(20);
        return view('admin.activity-log', compact('activities'));
    }
}
