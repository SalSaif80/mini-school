<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::user();
        $courses = Course::where('teacher_id', $teacher->id)->with('enrollments')->get();

        $stats = [
            'total_courses' => $courses->count(),
            'total_students' => $courses->sum(fn($course) => $course->enrollments->count()),
            'active_enrollments' => $courses->sum(fn($course) => $course->enrollments->where('status', 'active')->count()),
        ];

        return view('teacher.dashboard', compact('courses', 'stats'));
    }

    public function courses()
    {
        $teacher = Auth::user();
        $courses = Course::where('teacher_id', $teacher->id)
            ->with('enrollments.student')->get();

        return view('teacher.courses', compact('courses'));
    }

    public function courseStudents($courseId)
    {
        $teacher = Auth::user();
        $course = Course::where('teacher_id', $teacher->id)
            ->where('course_id', $courseId)
            ->firstOrFail();
        $enrollments = $course->enrollments()->with('student')->get();

        return view('teacher.course-students', compact('course', 'enrollments'));
    }

        public function updateGrade(Request $request, $enrollmentId)
    {
        $request->validate([
            'final_exam_grade' => 'required|numeric|min:0|max:100',
        ]);

        $enrollment = Enrollment::findOrFail($enrollmentId);

        // Check if the teacher owns the course
        $teacher = Auth::user();
        if ($enrollment->course->teacher_id !== $teacher->id) {
            abort(403, 'غير مصرح لك بتعديل درجات هذا الكورس.');
        }

        // حساب الدرجة النهائية والحالة
        $finalGrade = $request->final_exam_grade;
        $letterGrade = $this->calculateLetterGrade($finalGrade);
        $status = $finalGrade >= 50 ? 'completed' : 'failed';

        $enrollment->update([
            'final_exam_grade' => $finalGrade,
            'grade' => $letterGrade,
            'completion_date' => now(),
            'status' => $status,
        ]);

        // تسجيل النشاط
        activity()
            ->causedBy($teacher)
            ->log("تم تحديث درجة الطالب {$enrollment->student->name} في مادة {$enrollment->course->course_name}: {$finalGrade}%");

        return back()->with('success', 'تم تحديث الدرجة بنجاح');
    }

    public function downloadExamFile($enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $teacher = Auth::user();

        // التحقق من أن المدرس يملك الكورس
        if ($enrollment->course->teacher_id !== $teacher->id) {
            abort(403, 'غير مصرح لك بالوصول لهذا الملف.');
        }

        // التحقق من وجود الملف
        if (!$enrollment->exam_file_path || !Storage::disk('public')->exists($enrollment->exam_file_path)) {
            abort(404, 'الملف غير موجود.');
        }

        // تسجيل النشاط
        activity()
            ->causedBy($teacher)
            ->log("تم تحميل ملف اختبار الطالب {$enrollment->student->name} في مادة {$enrollment->course->course_name}");

        // تحميل الملف
        $filePath = Storage::disk('public')->path($enrollment->exam_file_path);
        $fileName = basename($enrollment->exam_file_path);

        return response()->download($filePath, $fileName);
    }

    public function viewExamFile($enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $teacher = Auth::user();

        // التحقق من أن المدرس يملك الكورس
        if ($enrollment->course->teacher_id !== $teacher->id) {
            abort(403, 'غير مصرح لك بالوصول لهذا الملف.');
        }

        // التحقق من وجود الملف
        if (!$enrollment->exam_file_path || !Storage::disk('public')->exists($enrollment->exam_file_path)) {
            abort(404, 'الملف غير موجود.');
        }

        // تسجيل النشاط
        activity()
            ->causedBy($teacher)
            ->log("تم عرض ملف اختبار الطالب {$enrollment->student->name} في مادة {$enrollment->course->course_name}");

                        // عرض الملف في المتصفح
        $filePath = Storage::disk('public')->path($enrollment->exam_file_path);

        return response()->file($filePath, [
            'Content-Disposition' => 'inline'
        ]);
    }

    private function calculateLetterGrade($score)
    {
        if ($score >= 95) return 'A+';
        if ($score >= 90) return 'A';
        if ($score >= 85) return 'B+';
        if ($score >= 80) return 'B';
        if ($score >= 75) return 'C+';
        if ($score >= 70) return 'C';
        if ($score >= 65) return 'D+';
        if ($score >= 60) return 'D';
        return 'F';
    }
}
