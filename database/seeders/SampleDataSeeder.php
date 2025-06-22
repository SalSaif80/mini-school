<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = User::where('user_type', 'teacher')->first();
        $students = User::where('user_type', 'student')->get();

        // Create sample courses
        $courses = [
            [
                'course_name' => 'مقدمة في البرمجة',
                'teacher_id' => $teacher->id,
                'schedule_date' => Carbon::now()->addDays(7)->setTime(10, 0),
                'room_number' => 'A101',
            ],
            [
                'course_name' => 'الرياضيات المتقدمة',
                'teacher_id' => $teacher->id,
                'schedule_date' => Carbon::now()->addDays(8)->setTime(14, 0),
                'room_number' => 'B202',
            ],
            [
                'course_name' => 'تصميم قواعد البيانات',
                'teacher_id' => $teacher->id,
                'schedule_date' => Carbon::now()->addDays(9)->setTime(9, 30),
                'room_number' => 'C303',
            ],
        ];

        foreach ($courses as $courseData) {
            $course = Course::create($courseData);

            // Enroll students in courses
            foreach ($students as $index => $student) {
                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->course_id,
                    'enrollment_date' => Carbon::now()->subDays(rand(1, 30)),
                    'semester' => '2024-2025 الفصل الأول',
                    'status' => $index == 0 ? 'completed' : 'active',
                    'grade' => $index == 0 ? 'A' : null,
                    'completion_date' => $index == 0 ? Carbon::now()->subDays(5) : null,
                ]);
            }
        }
    }
}
