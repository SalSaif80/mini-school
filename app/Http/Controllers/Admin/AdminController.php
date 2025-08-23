<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Spatie\Activitylog\Models\Activity;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;

use App\Http\Requests\Enrollment\StoreEnrollmentRequest;
use App\Http\Requests\Enrollment\UpdateEnrollmentRequest;


class AdminController extends Controller
{





    public function dashboard()
    {

        try {
            $stats = [
                'total_users' => User::count(),
                'total_courses' => Course::count(),
                'total_enrollments' => Enrollment::count(),
                'active_enrollments' => Enrollment::where('status', 'active')->count(),
            ];

            $recent_activities = Activity::latest()->take(10)->get();
            return view('admin.dashboard', compact('stats', 'recent_activities'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل لوحة التحكم');
        }
    }

    // ===== إدارة المستخدمين =====
    public function users()
    {
        try {
            $users = User::paginate(10);
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل قائمة المستخدمين');
        }
    }

    public function createUser()
    {
        try {
            $user_types = User::roleLabels();
            return view('admin.users.create', compact('user_types'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل صفحة إنشاء المستخدم');
        }
    }

    public function storeUser(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
            ]);

            // تعيين الـ role المناسب
            $user->assignRole($request->user_type);

            // // تسجيل النشاط
            // activity()
            //     ->causedBy(Auth::user())
            //     ->log('تم إنشاء مستخدم جديد: ' . $user->name);

            return redirect()->route('admin.users')
                ->with('success', 'تم إنشاء المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في إنشاء المستخدم')->withInput();
        }
    }

    public function showUser($id)
    {
        try {
            $user = User::findOrFail($id);
            // إحصائيات المستخدم
            $stats = [];
            if ($user->user_type === User::TEACHER) {
                $stats['courses'] = Course::where('teacher_id', $user->id)->count();
                $stats['students'] = Enrollment::whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                })->distinct('student_id')->count();
            } elseif ($user->user_type === User::STUDENT) {
                $stats['enrollments'] = Enrollment::where('student_id', $user->id)->count();
                $stats['completed'] = Enrollment::where('student_id', $user->id)
                    ->where('status', 'completed')->count();
            }

            return view('admin.users.show', compact('user', 'stats'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'المستخدم غير موجود');
        }
    }

    public function editUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user_types = User::roleLabels();
            return view('admin.users.edit', compact('user', 'user_types'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'المستخدم غير موجود');
        }
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

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
            // activity()
            //     ->causedBy(Auth::user())
            //     ->log('تم تحديث بيانات المستخدم: ' . $user->name);

            return redirect()->route('admin.users')
                ->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحديث المستخدم')->withInput();
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // منع حذف المستخدم الحالي
            if ($user->id === Auth::user()->id) {
                return redirect()->route('admin.users')
                    ->with('error', 'لا يمكنك حذف حسابك الشخصي');
            }

            $userName = $user->name;
            $user->delete();

            // تسجيل النشاط
            // activity()
            //     ->causedBy(Auth::user())
            //     ->log('تم حذف المستخدم: ' . $userName);

            return redirect()->route('admin.users')
                ->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'حدث خطأ في حذف المستخدم');
        }
    }

    // ===== إدارة الكورسات =====
    public function courses()
    {
        try {
            $courses = Course::with('teacher')
                ->withCount('enrollments')
                ->paginate(10);

            return view('admin.courses.index', compact('courses'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل قائمة الكورسات');
        }
    }

    public function createCourse()
    {
        try {
            $teachers = User::where('user_type', User::TEACHER)->get();
            return view('admin.courses.create', compact('teachers'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل صفحة إنشاء الكورس');
        }
    }

    public function storeCourse(StoreCourseRequest $request)
    {
        try {
            $course = Course::create($request->validated());

            // activity()
            //     ->causedBy(Auth::user())
            //     ->log('تم إنشاء كورس جديد: ' . $course->course_name);



            return redirect()->route('admin.courses')
                ->with('success', 'تم إنشاء الكورس بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في إنشاء الكورس')->withInput();
        }
    }

    public function showCourse($id)
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->route('admin.courses')->with('error', 'الكورس غير موجود');
        }
    }

    public function editCourse($id)
    {
        try {
            $course = Course::findOrFail($id);
            $teachers = User::where('user_type', User::TEACHER)->get();
            return view('admin.courses.edit', compact('course', 'teachers'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل صفحة تعديل الكورس');
        }
    }

    public function updateCourse(UpdateCourseRequest $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            $course->update($request->validated());

            // activity()
            //     ->causedBy(Auth::user())
            //     ->log('تم تحديث الكورس: ' . $course->course_name);

            return redirect()->route('admin.courses')
                ->with('success', 'تم تحديث الكورس بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحديث الكورس')->withInput();
        }
    }

    public function deleteCourse($id)
    {
        try {
            $course = Course::findOrFail($id);
            $courseName = $course->course_name;

            // حذف التسجيلات المرتبطة
            $course->enrollments()->delete();
            $course->delete();

            // activity()
            //     ->causedBy(Auth::user())
            //     ->log('تم حذف الكورس: ' . $courseName);

            return redirect()->route('admin.courses')
                ->with('success', 'تم حذف الكورس بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في حذف الكورس');
        }
    }

    public function courseStudents($id)
    {
        try {
            $course = Course::with(['teacher', 'enrollments.student'])
                ->findOrFail($id);
            return view('admin.courses.students', compact('course'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل صفحة طلاب الكورس');
        }
    }

    public function enrollments(Request $request)
    {
        try {
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
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%');
                });
            }

            $enrollments = $query->orderBy('created_at', 'desc')->paginate(15);

            // إحصائيات
            $stats = [
                'total' => Enrollment::count(),
                'active' => Enrollment::where('status', Enrollment::STATUS_ACTIVE)->count(),
                'completed' => Enrollment::where('status', Enrollment::STATUS_COMPLETED)->count(),
                'failed' => Enrollment::where('status', Enrollment::STATUS_FAILED)->count(),
                'dropped' => Enrollment::where('status', Enrollment::STATUS_DROPPED)->count(),
            ];

            // بيانات إضافية للفلاتر
            $courses = Course::with('teacher')->orderBy('course_name')->get();
            $students = User::where('user_type', User::STUDENT)->orderBy('name')->get();
            $semesters = Enrollment::distinct()->pluck('semester')->filter()->sort()->values();
            return view('admin.enrollments.index', compact('enrollments', 'stats', 'courses', 'students', 'semesters'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل قائمة التسجيلات');
        }
    }

    public function storeEnrollment(StoreEnrollmentRequest $request)
    {
        try {
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
                'status' => Enrollment::STATUS_ACTIVE,
            ]);

            // activity()
            //     ->causedBy(Auth::user())
            //     ->performedOn($student)
            //     ->log("تم تسجيل الطالب {$student->name} في كورس {$course->course_name}");

            return redirect()->route('admin.enrollments')
                ->with('success', "تم تسجيل الطالب {$student->name} في كورس {$course->course_name} بنجاح");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تسجيل الطالب')->withInput();
        }
    }

    public function showEnrollment($id)
    {
        try {
            $enrollment = Enrollment::with([
                'student.enrollments',
                'course.teacher',
                'course.enrollments',
            ])->findOrFail($id);

            return view('admin.enrollments.show', compact('enrollment'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل تفاصيل التسجيل');
        }
    }

    public function editEnrollment($id)
    {
        try {
            $enrollment = Enrollment::with(['student', 'course'])->findOrFail($id);
            $courses = Course::with('teacher')->orderBy('course_name')->get();
            $students = User::where('user_type', 'student')->where('id', '=', $enrollment->student_id)->orderBy('name')->get();
            return view('admin.enrollments.edit', compact('enrollment', 'courses', 'students'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل صفحة تعديل التسجيل');
        }
    }

    public function updateEnrollment(UpdateEnrollmentRequest $request, $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);

            if ($request->student_id != $enrollment->student_id || $request->course_id != $enrollment->course_id) {
                $existingEnrollment = Enrollment::where('student_id', $request->student_id)
                    ->where('course_id', $request->course_id)
                    ->where('enrollment_id', '!=', $id)
                    ->first();

                if ($existingEnrollment) {
                    return back()->with('error', 'الطالب مسجل في هذا الكورس مسبقاً');
                }
            }

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

            $newStudentData = User::find($request->student_id);
            $newCourseData = Course::find($request->course_id);

            // activity()
            //     ->causedBy(Auth::user())
            //     ->performedOn($newStudentData)
            //     ->log("تم تحديث تسجيل الطالب {$newStudentData->name} في كورس {$newCourseData->course_name}");

            return redirect()->route('admin.enrollments')
                ->with('success', 'تم تحديث التسجيل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحديث التسجيل')->withInput();
        }
    }

    public function deleteEnrollment($id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $userName = $enrollment->student->name;
            $courseName = $enrollment->course->course_name;
            $enrollment->delete();

            // activity()
            //     ->causedBy(Auth::user())
            //     ->performedOn($enrollment->student)
            //     ->log("تم حذف تسجيل الطالب {$userName} من كورس {$courseName}");

            return redirect()->route('admin.enrollments')
                ->with('success', 'تم حذف التسجيل بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في حذف التسجيل');
        }
    }

    public function activityLog(Request $request)
    {
        try {
            $query = Activity::with(['causer', 'subject']);

            // فلتر حسب المستخدم
            if ($request->filled('causer_id')) {
                $query->where('causer_id', $request->causer_id);
            }

            // فلتر حسب نوع النموذج
            if ($request->filled('subject_type')) {
                $query->where('subject_type', $request->subject_type);
            }

            // فلتر حسب نوع العملية (البحث في النص)
            if ($request->filled('operation_type')) {
                $operationMap = [
                    'create' => ['إنشاء', 'تم إنشاء', 'created'],
                    'update' => ['تحديث', 'تم تحديث', 'updated'],
                    'delete' => ['حذف', 'تم حذف', 'deleted'],
                    'login' => ['تسجيل الدخول', 'دخول'],
                    'logout' => ['تسجيل الخروج', 'خروج'],
                    'enroll' => ['تسجيل', 'تم تسجيل'],
                ];

                if (isset($operationMap[$request->operation_type])) {
                    $searchTerms = $operationMap[$request->operation_type];
                    $query->where(function ($q) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $q->orWhere('description', 'like', '%' . $term . '%');
                        }
                    });
                }
            }

            // فلتر حسب التاريخ
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // البحث في النص
            if ($request->filled('search')) {
                $query->where('description', 'like', '%' . $request->search . '%');
            }

            // التصدير إلى Excel
            if ($request->has('export') && $request->export === 'excel') {
                return $this->exportActivitiesToExcel($query);
            }

            $activities = $query->latest()->paginate(20)->appends($request->query());

            // البيانات المساعدة للفلاتر
            $users = User::orderBy('name')->get();
            $subjectTypes = Activity::distinct()
                ->whereNotNull('subject_type')
                ->pluck('subject_type')
                ->map(function ($type) {
                    return [
                        'value' => $type,
                        'label' => $this->getSubjectTypeLabel($type)
                    ];
                });

            // إحصائيات
            $stats = [
                'total' => Activity::count(),
                'today' => Activity::whereDate('created_at', today())->count(),
                'this_week' => Activity::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => Activity::whereMonth('created_at', now()->month)->count(),
            ];

            return view('admin.activity-log', compact('activities', 'users', 'subjectTypes', 'stats'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تحميل سجل النشاطات');
        }
    }

    private function getSubjectTypeLabel($type)
    {
        $labels = [
            'App\Models\User' => 'المستخدمين',
            'App\Models\Course' => 'الكورسات',
            'App\Models\Enrollment' => 'التسجيلات',
        ];

        return $labels[$type] ?? $type;
    }

    private function exportActivitiesToExcel($query)
    {
        try {
            $activities = $query->latest()->get();

            $filename = 'activity_log_' . now()->format('Y_m_d_H_i_s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($activities) {
                $file = fopen('php://output', 'w');

                // إضافة BOM للـ UTF-8
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // كتابة العناوين
                fputcsv($file, [
                    'الرقم',
                    'المستخدم',
                    'النوع',
                    'النشاط',
                    'التاريخ',
                    'الوقت'
                ], ',', '"');

                // كتابة البيانات
                foreach ($activities as $index => $activity) {
                    fputcsv($file, [
                        $index + 1,
                        $activity->causer ? $activity->causer->name : 'نظام',
                        $activity->subject_type ? $this->getSubjectTypeLabel($activity->subject_type) : 'عام',
                        $activity->description,
                        $activity->created_at->format('Y-m-d'),
                        $activity->created_at->format('H:i:s')
                    ], ',', '"');
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ في تصدير البيانات');
        }
    }
}
