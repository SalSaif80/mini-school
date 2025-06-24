<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Course\UploadExamFileCourseRequest;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user();
        $enrollments = Enrollment::with('course.teacher')->where('student_id', $student->id)->get();

        $stats = [
            'total_courses' => $enrollments->count(),
            'active_courses' => $enrollments->where('status', 'active')->count(),
            'completed_courses' => $enrollments->where('status', 'completed')->count(),
            'dropped_courses' => $enrollments->where('status', 'dropped')->count(),
        ];

        return view('student.dashboard', compact('enrollments', 'stats'));
    }

    public function courses()
    {
        $student = Auth::user();
        $enrollments = Enrollment::with('course.teacher')->where('student_id', $student->id)->get();

        return view('student.courses', compact('enrollments'));
    }

    public function grades()
    {
        $student = Auth::user();
        $enrollments = Enrollment::with('course.teacher')->where('student_id', $student->id)->whereNotNull('grade')->get();

        return view('student.grades', compact('enrollments'));
    }

    public function uploadExamFile(UploadExamFileCourseRequest $request, $enrollmentId)
    {
        try {
            $enrollment = Enrollment::findOrFail($enrollmentId);

            // التأكد أن الطالب يملك هذا التسجيل
            if ($enrollment->student_id !== Auth::user()->id) {
                abort(403, 'غير مصرح لك بهذا الإجراء');
            }

            // التأكد أن الكورس ما زال نشطاً
            if ($enrollment->status !== Enrollment::STATUS_ACTIVE) {
                return back()->with('error', 'لا يمكن رفع الملف لأن حالة التسجيل غير نشطة');
            }

            $file = $request->file('exam_file');
            $fileName = time() . '_' . $enrollment->student_id . '_' . $enrollment->course_id . '.' . $file->getClientOriginalExtension();

            // حفظ الملف في مجلد exam_files
            $filePath = $file->storeAs('exam_files', $fileName, 'public');

            // حذف الملف القديم إذا وجد
            if ($enrollment->exam_file_path && Storage::disk('public')->exists($enrollment->exam_file_path)) {
                Storage::disk('public')->delete($enrollment->exam_file_path);
            }

            // تحديث قاعدة البيانات
            $enrollment->update([
                'exam_file_path' => $filePath
            ]);

            // تسجيل النشاط
            activity()
                ->causedBy(Auth::user())
                ->performedOn($enrollment->student)
                ->log("تم رفع ملف الاختبار لمادة {$enrollment->course->course_name}");

            return back()->with('success', 'تم رفع ملف الاختبار بنجاح');
        } catch (\Throwable $th) {
            return back()->with('error', 'حدث خطأ ما أثناء رفع الملف');
        }
    }

    public function deleteExamFile($enrollmentId)
    {
        try {
            $enrollment = Enrollment::findOrFail($enrollmentId);

            // التأكد أن الطالب يملك هذا التسجيل
            if ($enrollment->student_id !== Auth::user()->id) {
                abort(403, 'غير مصرح لك بهذا الإجراء');
            }

            if ($enrollment->exam_file_path && Storage::disk('public')->exists($enrollment->exam_file_path)) {
                Storage::disk('public')->delete($enrollment->exam_file_path);
            }

            $enrollment->update([
                'exam_file_path' => null
            ]);

            activity()
                ->causedBy(Auth::user())
                ->performedOn($enrollment->student)
                ->log("تم حذف ملف الاختبار لمادة {$enrollment->course->course_name}");

            return back()->with('success', 'تم حذف ملف الاختبار بنجاح');
        } catch (\Throwable $th) {
            return back()->with('error', 'حدث خطأ ما');
        }
    }

    public function availableCourses()
    {
        $student = Auth::user();

        // الحصول على الكورسات التي لم يسجل فيها الطالب
        $enrolledCourseIds = Enrollment::where('student_id', $student->id)
            ->pluck('course_id')
            ->toArray();

        $availableCourses = Course::with('teacher')->whereNotIn('course_id', $enrolledCourseIds)->orderBy('course_name')->get();

        return view('student.available-courses', compact('availableCourses'));
    }

    public function enrollInCourse(Request $request, $courseId)
    {
        try {
            $student = Auth::user();
            $course = Course::findOrFail($courseId);

            // التحقق من أن الطالب غير مسجل في الكورس
            $existingEnrollment = Enrollment::where('student_id', $student->id)
                ->where('course_id', $courseId)
                ->first();

            if ($existingEnrollment) {
                return back()->with('error', 'أنت مسجل في هذا الكورس مسبقاً');
            }

            // إنشاء تسجيل جديد
            $enrollment = Enrollment::create([
                'student_id' => $student->id,
                'course_id' => $courseId,
                'enrollment_date' => now(),
                'semester' => '2025-1', // يمكن تخصيص هذا حسب النظام
                'status' => 'active',
            ]);

            // تسجيل النشاط
            activity()
                ->causedBy($student)
                ->performedOn($enrollment->student)
                ->log("تم التسجيل في كورس: {$course->course_name}");

            return redirect()->route('student.courses')
                ->with('success', "تم تسجيلك في كورس {$course->course_name} بنجاح");
        } catch (\Throwable $th) {
            return back()->with('error', 'حدث خطأ ما أثناء التسجيل في الكورس');
        }
    }

    // انسحاب الطالب من الكورس
    public function withdrawFromCourse($enrollmentId)
    {
        try {
            $student = Auth::user();
            $enrollment = Enrollment::where('enrollment_id', $enrollmentId)
                ->where('student_id', $student->id)
                ->firstOrFail();

            // التحقق من أن التسجيل ما زال نشطاً
            if ($enrollment->status !== Enrollment::STATUS_ACTIVE) {
                return back()->with('error', 'لا يمكن الانسحاب من كورس غير نشط');
            }

            $courseName = $enrollment->course->course_name;
            $enrollment->update([
                'status' => Enrollment::STATUS_DROPPED,
                'completion_date' => now(),
            ]);

            // تسجيل النشاط
            activity()
                ->causedBy($student)
                ->performedOn($enrollment->student)
                ->log("تم الانسحاب من كورس: {$courseName}");

            return back()->with('success', "تم انسحابك من كورس {$courseName}");
        } catch (\Throwable $th) {
            return back()->with('error', 'حدث خطأ ما');
        }
    }
}
