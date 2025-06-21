<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the teachers.
     */
    public function index(Request $request)
    {
        // Only admin can view all teachers
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard-school')->with('error', 'ليس لديك صلاحية لعرض قائمة المدرسين');
        }

        $query = Teacher::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('teacher_id', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%")
              ->orWhere('specialization', 'like', "%{$search}%");
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', 'like', "%{$request->department}%");
        }

        // Filter by specialization
        if ($request->filled('specialization')) {
            $query->where('specialization', 'like', "%{$request->specialization}%");
        }

        $teachers = $query->paginate(15);

        return view('dashboard.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        // Only admin can create teachers
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('teachers.index')->with('error', 'ليس لديك صلاحية إنشاء مدرس جديد');
        }

        return view('dashboard.teachers.create');
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create teachers
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('teachers.index')->with('error', 'ليس لديك صلاحية إنشاء مدرس جديد');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'teacher_id' => 'required|string|max:20|unique:teachers',
            'hire_date' => 'required|date|before_or_equal:today',
            'department' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'teacher_id.required' => 'رقم المدرس مطلوب',
            'teacher_id.unique' => 'رقم المدرس موجود مسبقاً',
            'hire_date.required' => 'تاريخ التوظيف مطلوب',
            'hire_date.before_or_equal' => 'تاريخ التوظيف لا يمكن أن يكون في المستقبل',
            'department.required' => 'القسم مطلوب',
            'specialization.required' => 'التخصص مطلوب',
            'salary.required' => 'الراتب مطلوب',
            'salary.numeric' => 'الراتب يجب أن يكون رقماً',
            'salary.min' => 'الراتب لا يمكن أن يكون سالباً',
        ]);

        // Create user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        // Create teacher profile
        Teacher::create([
            'user_id' => $user->id,
            'teacher_id' => $request->teacher_id,
            'hire_date' => $request->hire_date,
            'department' => $request->department,
            'specialization' => $request->specialization,
            'salary' => $request->salary,
        ]);

        return redirect()->route('teachers.index')->with('success', 'تم إنشاء المدرس بنجاح');
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->isAdmin() &&
            !($user->isTeacher() && $user->teacher && $user->teacher->id === $teacher->id)) {
            return redirect()->route('teachers.index')->with('error', 'ليس لديك صلاحية لعرض بيانات هذا المدرس');
        }

        $teacher->load(['user', 'courses.enrollments.student.user']);

        return view('dashboard.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->isAdmin() &&
            !($user->isTeacher() && $user->teacher && $user->teacher->id === $teacher->id)) {
            return redirect()->route('teachers.index')->with('error', 'ليس لديك صلاحية لتعديل بيانات هذا المدرس');
        }

        return view('dashboard.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->isAdmin() &&
            !($user->isTeacher() && $user->teacher && $user->teacher->id === $teacher->id)) {
            return redirect()->route('teachers.index')->with('error', 'ليس لديك صلاحية لتعديل بيانات هذا المدرس');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user->id,
            'teacher_id' => 'required|string|max:20|unique:teachers,teacher_id,' . $teacher->id,
            'hire_date' => 'required|date|before_or_equal:today',
            'department' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
        ];

        // Only admin can update salary and password
        if ($user->isAdmin()) {
            $rules['salary'] = 'required|numeric|min:0';
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:8';
            }
        }

        $request->validate($rules, [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'teacher_id.required' => 'رقم المدرس مطلوب',
            'teacher_id.unique' => 'رقم المدرس موجود مسبقاً',
            'hire_date.required' => 'تاريخ التوظيف مطلوب',
            'hire_date.before_or_equal' => 'تاريخ التوظيف لا يمكن أن يكون في المستقبل',
            'department.required' => 'القسم مطلوب',
            'specialization.required' => 'التخصص مطلوب',
            'salary.required' => 'الراتب مطلوب',
            'salary.numeric' => 'الراتب يجب أن يكون رقماً',
            'salary.min' => 'الراتب لا يمكن أن يكون سالباً',
        ]);

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($user->isAdmin() && $request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $teacher->user->update($userData);

        // Update teacher data
        $teacherData = [
            'teacher_id' => $request->teacher_id,
            'hire_date' => $request->hire_date,
            'department' => $request->department,
            'specialization' => $request->specialization,
        ];

        if ($user->isAdmin()) {
            $teacherData['salary'] = $request->salary;
        }

        $teacher->update($teacherData);

        return redirect()->route('teachers.index')->with('success', 'تم تحديث بيانات المدرس بنجاح');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(Teacher $teacher)
    {
        // Only admin can delete teachers
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('teachers.index')->with('error', 'ليس لديك صلاحية لحذف هذا المدرس');
        }

        // Check if teacher has courses
        if ($teacher->courses()->count() > 0) {
            return redirect()->route('teachers.index')->with('error', 'لا يمكن حذف المدرس لأنه مكلف بمواد دراسية');
        }

        // Delete the user (which will cascade delete the teacher due to foreign key)
        $teacher->user->delete();

        return redirect()->route('teachers.index')->with('success', 'تم حذف المدرس بنجاح');
    }

    /**
     * Get teacher statistics
     */
    public function statistics()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('teachers.index')->with('error', 'ليس لديك صلاحية لعرض الإحصائيات');
        }

        $stats = [
            'total_teachers' => Teacher::count(),
            'teachers_by_department' => Teacher::selectRaw('department, COUNT(*) as count')
                                             ->groupBy('department')
                                             ->pluck('count', 'department')
                                             ->toArray(),
            'teachers_by_specialization' => Teacher::selectRaw('specialization, COUNT(*) as count')
                                                  ->groupBy('specialization')
                                                  ->pluck('count', 'specialization')
                                                  ->toArray(),
            'average_salary' => Teacher::avg('salary'),
            'salary_distribution' => [
                'below_5000' => Teacher::where('salary', '<', 5000)->count(),
                '5000_10000' => Teacher::whereBetween('salary', [5000, 10000])->count(),
                '10000_15000' => Teacher::whereBetween('salary', [10000, 15000])->count(),
                'above_15000' => Teacher::where('salary', '>', 15000)->count(),
            ],
            'hire_by_year' => Teacher::selectRaw('YEAR(hire_date) as year, COUNT(*) as count')
                                    ->groupBy('year')
                                    ->orderBy('year')
                                    ->pluck('count', 'year')
                                    ->toArray(),
        ];

        return response()->json($stats);
    }
}
