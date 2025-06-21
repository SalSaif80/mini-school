<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        // Only admin can view all students
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard-school')->with('error', 'ليس لديك صلاحية لعرض قائمة الطلاب');
        }

        $query = Student::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('student_id', 'like', "%{$search}%")
              ->orWhere('major', 'like', "%{$search}%");
        }

        // Filter by class level
        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }

        // Filter by major
        if ($request->filled('major')) {
            $query->where('major', 'like', "%{$request->major}%");
        }

        $students = $query->paginate(15);

        return view('dashboard.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        // Only admin can create students
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('students.index')->with('error', 'ليس لديك صلاحية إنشاء طالب جديد');
        }

        return view('dashboard.students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create students
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('students.index')->with('error', 'ليس لديك صلاحية إنشاء طالب جديد');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'student_id' => 'required|string|max:20|unique:students',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'enrollment_date' => 'required|date',
            'major' => 'required|string|max:100',
            'class_level' => 'required|string|max:50',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'student_id.required' => 'رقم الطالب مطلوب',
            'student_id.unique' => 'رقم الطالب موجود مسبقاً',
            'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
            'date_of_birth.before' => 'تاريخ الميلاد يجب أن يكون في الماضي',
            'gender.required' => 'الجنس مطلوب',
            'address.required' => 'العنوان مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'enrollment_date.required' => 'تاريخ التسجيل مطلوب',
            'major.required' => 'التخصص مطلوب',
            'class_level.required' => 'المستوى الدراسي مطلوب',
        ]);

        // Create user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Create student profile
        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone' => $request->phone,
            'enrollment_date' => $request->enrollment_date,
            'major' => $request->major,
            'class_level' => $request->class_level,
        ]);

        return redirect()->route('students.index')->with('success', 'تم إنشاء الطالب بنجاح');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->isAdmin() &&
            !($user->isStudent() && $user->student && $user->student->id === $student->id)) {
            return redirect()->route('students.index')->with('error', 'ليس لديك صلاحية لعرض بيانات هذا الطالب');
        }

        $student->load(['user', 'enrollments.course.teacher.user']);

        return view('dashboard.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->isAdmin() &&
            !($user->isStudent() && $user->student && $user->student->id === $student->id)) {
            return redirect()->route('students.index')->with('error', 'ليس لديك صلاحية لتعديل بيانات هذا الطالب');
        }

        return view('dashboard.students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->isAdmin() &&
            !($user->isStudent() && $user->student && $user->student->id === $student->id)) {
            return redirect()->route('students.index')->with('error', 'ليس لديك صلاحية لتعديل بيانات هذا الطالب');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user->id,
            'student_id' => 'required|string|max:20|unique:students,student_id,' . $student->id,
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'enrollment_date' => 'required|date',
            'major' => 'required|string|max:100',
            'class_level' => 'required|string|max:50',
        ];

        // Only admin can update password
        if ($user->isAdmin() && $request->filled('password')) {
            $rules['password'] = 'string|min:8';
        }

        $request->validate($rules, [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'student_id.required' => 'رقم الطالب مطلوب',
            'student_id.unique' => 'رقم الطالب موجود مسبقاً',
            'date_of_birth.required' => 'تاريخ الميلاد مطلوب',
            'date_of_birth.before' => 'تاريخ الميلاد يجب أن يكون في الماضي',
            'gender.required' => 'الجنس مطلوب',
            'address.required' => 'العنوان مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'enrollment_date.required' => 'تاريخ التسجيل مطلوب',
            'major.required' => 'التخصص مطلوب',
            'class_level.required' => 'المستوى الدراسي مطلوب',
        ]);

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($user->isAdmin() && $request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $student->user->update($userData);

        // Update student data
        $student->update([
            'student_id' => $request->student_id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone' => $request->phone,
            'enrollment_date' => $request->enrollment_date,
            'major' => $request->major,
            'class_level' => $request->class_level,
        ]);

        return redirect()->route('students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Only admin can delete students
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('students.index')->with('error', 'ليس لديك صلاحية لحذف هذا الطالب');
        }

        // Delete the user (which will cascade delete the student due to foreign key)
        $student->user->delete();

        return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح');
    }

    /**
     * Get student statistics
     */
    public function statistics()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('students.index')->with('error', 'ليس لديك صلاحية لعرض الإحصائيات');
        }

        $stats = [
            'total_students' => Student::count(),
            'students_by_class' => Student::selectRaw('class_level, COUNT(*) as count')
                                        ->groupBy('class_level')
                                        ->pluck('count', 'class_level')
                                        ->toArray(),
            'students_by_major' => Student::selectRaw('major, COUNT(*) as count')
                                        ->groupBy('major')
                                        ->pluck('count', 'major')
                                        ->toArray(),
            'students_by_gender' => Student::selectRaw('gender, COUNT(*) as count')
                                         ->groupBy('gender')
                                         ->pluck('count', 'gender')
                                         ->toArray(),
            'enrollment_by_month' => Student::selectRaw('DATE_FORMAT(enrollment_date, "%Y-%m") as month, COUNT(*) as count')
                                          ->groupBy('month')
                                          ->orderBy('month')
                                          ->pluck('count', 'month')
                                          ->toArray(),
        ];

        return response()->json($stats);
    }
}
