<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Cource\StoreCourceRequest;
use App\Http\Requests\Cource\UpdateCourceRequest;

class CourseController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:استعراض المواد الدراسية', ['only' => ['index']]);
        // $this->middleware('permission:إنشاء مواد دراسية', ['only' => ['create', 'store']]);
        // $this->middleware('permission:عرض تفاصيل المادة', ['only' => ['show']]);
        // $this->middleware('permission:تعديل المواد الدراسية', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:حذف المواد الدراسية', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with(['teacher.user', 'enrollments.student.user'])->paginate(10);
        return view('dashboard.admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        // // Only admin can create courses
        // if (!Auth::user()->isAdmin()) {
        //     return redirect()->route('courses.index')->with('error', 'ليس لديك صلاحية إنشاء دورة جديدة');
        // }

        $teachers = Teacher::with('user')->get();
        return view('dashboard.admin.courses.create', compact('teachers'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(StoreCourceRequest $request)
    {

        Course::create($request->all());

        return redirect()->route('courses.index')->with('success', 'تم إنشاء المادة بنجاح');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $user = Auth::user();

        $course->load(['teacher.user', 'enrollments.student.user']);

        return view('dashboard.admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $user = Auth::user();

        $teachers = Teacher::with('user')->get();
        // return $course;
        return view('dashboard.admin.courses.edit', compact('course', 'teachers'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(UpdateCourceRequest $request, Course $course)
    {
        $course->update($request->only(['course_code', 'title', 'description', 'credit_hours', 'level', 'teacher_id']));
        return redirect()->route('courses.index')->with('success', 'تم تحديث المادة بنجاح');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Check if there are enrolled students
        if ($course->enrollments()->count() > 0) {
            return redirect()->route('courses.index')->with('error', 'لا يمكن حذف المادة لأن هناك ' . $course->enrollments()->count() . ' طالب مسجل فيها. يجب إلغاء تسجيل الطلاب أولاً.');
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'تم حذف المادة بنجاح');
    }

    /**
     * Display students enrolled in the course.
     */
    public function students(Course $course)
    {
        $course->load(['teacher.user', 'enrollments.student.user']);

        return view('dashboard.admin.courses.students', compact('course'));
    }


}
