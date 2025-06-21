<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('No students or courses found. Please run UserSeeder and CourseSeeder first.');
            return;
        }

        $enrollments = [];
        $enrollmentCount = 0;

        // Create 25-30 random enrollments
        for ($i = 0; $i < 30; $i++) {
            $student = $students->random();
            $course = $courses->random();

            // Check if this combination already exists
            $exists = collect($enrollments)->contains(function ($enrollment) use ($student, $course) {
                return $enrollment['student_id'] == $student->id && $enrollment['course_id'] == $course->id;
            });

            if (!$exists) {
                $status = fake()->randomElement(['active', 'active', 'active', 'completed', 'completed', 'dropped']);
                $grade = null;

                if ($status === 'completed') {
                    $grade = fake()->randomElement([85.5, 78.0, 92.5, 88.0, 75.5, 95.0, 82.5, 90.0, 87.5, 79.0]);
                }

                $enrollments[] = [
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'enrollment_date' => fake()->dateTimeBetween('2024-01-01', '2024-12-31')->format('Y-m-d'),
                    'status' => $status,
                    'grade' => $grade,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $enrollmentCount++;
            }
        }

        // Insert all enrollments
        Enrollment::insert($enrollments);

        $this->command->info("تم إنشاء {$enrollmentCount} تسجيل بنجاح");
    }
}
