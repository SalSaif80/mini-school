<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the enrollments.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['student.user', 'course.teacher.user'])->paginate(10);
        return view('dashboard.enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create()
    {
        $students = Student::with('user')->get();
        $courses = Course::with('teacher.user')->get();

        return view('dashboard.enrollments.create', compact('students', 'courses'));
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,completed,dropped',
            'grade' => 'nullable|numeric|min:0|max:100',
        ], [
            'student_id.required' => 'الطالب مطلوب',
            'student_id.exists' => 'الطالب المحدد غير موجود',
            'course_id.required' => 'المادة مطلوبة',
            'course_id.exists' => 'المادة المحددة غير موجودة',
            'enrollment_date.required' => 'تاريخ التسجيل مطلوب',
            'enrollment_date.date' => 'تاريخ التسجيل يجب أن يكون تاريخ صحيح',
            'status.required' => 'حالة التسجيل مطلوبة',
            'status.in' => 'حالة التسجيل يجب أن تكون نشط أو مكتمل أو منسحب',
            'grade.numeric' => 'الدرجة يجب أن تكون رقماً',
            'grade.min' => 'الدرجة لا يمكن أن تكون أقل من 0',
            'grade.max' => 'الدرجة لا يمكن أن تكون أكثر من 100',
        ]);

        // Check for duplicate enrollment
        $existingEnrollment = Enrollment::where('student_id', $request->student_id)
                                      ->where('course_id', $request->course_id)
                                      ->first();

        if ($existingEnrollment) {
            return back()->withErrors(['student_id' => 'الطالب مسجل في هذه المادة مسبقاً'])->withInput();
        }

        Enrollment::create($request->all());

        return redirect()->route('enrollments.index')->with('success', 'تم إنشاء التسجيل بنجاح');
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student.user', 'course.teacher.user']);
        return view('dashboard.enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit(Enrollment $enrollment)
    {
        $students = Student::with('user')->get();
        $courses = Course::with('teacher.user')->get();

        return view('dashboard.enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    /**
     * Update the specified enrollment in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,completed,dropped',
            'grade' => 'nullable|numeric|min:0|max:100',
        ], [
            'student_id.required' => 'الطالب مطلوب',
            'student_id.exists' => 'الطالب المحدد غير موجود',
            'course_id.required' => 'المادة مطلوبة',
            'course_id.exists' => 'المادة المحددة غير موجودة',
            'enrollment_date.required' => 'تاريخ التسجيل مطلوب',
            'enrollment_date.date' => 'تاريخ التسجيل يجب أن يكون تاريخ صحيح',
            'status.required' => 'حالة التسجيل مطلوبة',
            'status.in' => 'حالة التسجيل يجب أن تكون نشط أو مكتمل أو منسحب',
            'grade.numeric' => 'الدرجة يجب أن تكون رقماً',
            'grade.min' => 'الدرجة لا يمكن أن تكون أقل من 0',
            'grade.max' => 'الدرجة لا يمكن أن تكون أكثر من 100',
        ]);

        $enrollment->update($request->all());

        return redirect()->route('enrollments.index')->with('success', 'تم تحديث التسجيل بنجاح');
    }

    /**
     * Remove the specified enrollment from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return redirect()->route('enrollments.index')->with('success', 'تم حذف التسجيل بنجاح');
    }

    /**
     * Mark enrollment as completed
     */
    public function markCompleted(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
        ], [
            'grade.required' => 'الدرجة مطلوبة',
            'grade.numeric' => 'الدرجة يجب أن تكون رقماً',
            'grade.min' => 'الدرجة لا يمكن أن تكون أقل من 0',
            'grade.max' => 'الدرجة لا يمكن أن تكون أكثر من 100',
        ]);

        $enrollment->markAsCompleted($request->grade);

        return redirect()->back()->with('success', 'تم تسجيل إكمال المادة بنجاح');
    }

    /**
     * Mark enrollment as dropped
     */
    public function markDropped(Enrollment $enrollment)
    {
        $enrollment->markAsDropped();

        return redirect()->back()->with('success', 'تم تسجيل انسحاب الطالب من المادة');
    }

    /**
     * Get enrollment statistics
     */
    public function statistics()
    {
        $stats = [
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'completed_enrollments' => Enrollment::where('status', 'completed')->count(),
            'dropped_enrollments' => Enrollment::where('status', 'dropped')->count(),
            'monthly_enrollments' => Enrollment::selectRaw('DATE_FORMAT(enrollment_date, "%Y-%m") as month, COUNT(*) as count')
                                              ->groupBy('month')
                                              ->orderBy('month')
                                              ->pluck('count', 'month')
                                              ->toArray(),
            'grade_distribution' => Enrollment::whereNotNull('grade')
                ->selectRaw('
                    CASE
                        WHEN grade >= 90 THEN "A"
                        WHEN grade >= 80 THEN "B"
                        WHEN grade >= 70 THEN "C"
                        WHEN grade >= 60 THEN "D"
                        ELSE "F"
                    END as letter_grade,
                    COUNT(*) as count
                ')
                ->groupBy('letter_grade')
                ->pluck('count', 'letter_grade')
                ->toArray(),
        ];

        return response()->json($stats);
    }
}
