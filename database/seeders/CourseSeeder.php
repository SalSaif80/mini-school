<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Teacher;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::all();

        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Please run UserSeeder first.');
            return;
        }

        $courses = [
            [
                'course_code' => 'MATH101',
                'title' => 'الجبر الأساسي',
                'description' => 'مقدمة في الجبر والمعادلات الخطية والتربيعية',
                'credit_hours' => 3,
                'level' => 'beginner',
            ],
            [
                'course_code' => 'ARAB201',
                'title' => 'النحو والصرف',
                'description' => 'دراسة قواعد اللغة العربية والنحو والصرف',
                'credit_hours' => 4,
                'level' => 'intermediate',
            ],
            [
                'course_code' => 'PHYS301',
                'title' => 'الفيزياء العامة',
                'description' => 'مبادئ الفيزياء الكلاسيكية والحديثة',
                'credit_hours' => 4,
                'level' => 'advanced',
            ],
            [
                'course_code' => 'HIST101',
                'title' => 'التاريخ الإسلامي',
                'description' => 'دراسة التاريخ الإسلامي من البداية حتى العصر الحديث',
                'credit_hours' => 3,
                'level' => 'beginner',
            ],
            [
                'course_code' => 'MATH201',
                'title' => 'التفاضل والتكامل',
                'description' => 'مقدمة في حساب التفاضل والتكامل',
                'credit_hours' => 4,
                'level' => 'intermediate',
            ],
            [
                'course_code' => 'CHEM101',
                'title' => 'الكيمياء العامة',
                'description' => 'أساسيات الكيمياء والتفاعلات الكيميائية',
                'credit_hours' => 3,
                'level' => 'beginner',
            ],
            [
                'course_code' => 'BIO201',
                'title' => 'علم الأحياء',
                'description' => 'دراسة الكائنات الحية ووظائفها',
                'credit_hours' => 4,
                'level' => 'intermediate',
            ],
            [
                'course_code' => 'GEO101',
                'title' => 'الجغرافيا الطبيعية',
                'description' => 'دراسة الظواهر الجغرافية الطبيعية',
                'credit_hours' => 3,
                'level' => 'beginner',
            ],
            [
                'course_code' => 'CS101',
                'title' => 'مقدمة في البرمجة',
                'description' => 'أساسيات البرمجة والخوارزميات',
                'credit_hours' => 3,
                'level' => 'beginner',
            ],
            [
                'course_code' => 'ARAB301',
                'title' => 'الأدب العربي',
                'description' => 'دراسة الأدب العربي عبر العصور',
                'credit_hours' => 3,
                'level' => 'advanced',
            ],
        ];

        foreach ($courses as $courseData) {
            Course::create([
                'course_code' => $courseData['course_code'],
                'title' => $courseData['title'],
                'description' => $courseData['description'],
                'credit_hours' => $courseData['credit_hours'],
                'teacher_id' => $teachers->random()->id,
                'level' => $courseData['level'],
            ]);
        }

        $this->command->info('تم إنشاء ' . count($courses) . ' دورة بنجاح');
    }
}
