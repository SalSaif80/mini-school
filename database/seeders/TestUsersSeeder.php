<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم إدارة
        $admin = User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'المدير العام',
                'password' => Hash::make('password123'),
                'role' => User::ADMIN_ROLE,
                'phone' => '01234567890',
                'address' => 'مكتب الإدارة',
                'gender' => 'male',
                'date_of_birth' => '1980-01-01',
                'hire_date' => now(),
            ]
        );

        // إنشاء مستخدم معلم
        $teacherUser = User::firstOrCreate(
            ['email' => 'teacher@school.com'],
            [
                'name' => 'أحمد محمد المعلم',
                'password' => Hash::make('password123'),
                'role' => User::TEACHER_ROLE,
                'phone' => '01111111111',
                'address' => 'الرياض، المملكة العربية السعودية',
                'gender' => 'male',
                'date_of_birth' => '1985-05-15',
                'hire_date' => now()->subYears(2),
            ]
        );

        // إنشاء ملف المعلم
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $teacherUser->id],
            [
                'teacher_id' => 'T001',
                'phone' => $teacherUser->phone,
                'address' => $teacherUser->address,
                'gender' => $teacherUser->gender,
                'date_of_birth' => $teacherUser->date_of_birth,
                'department' => 'الرياضيات',
                'specialization' => 'الرياضيات والعلوم',
                'hire_date' => $teacherUser->hire_date,
                'salary' => 5000.00,
            ]
        );

                // إنشاء مستخدم طالب
        $studentUser = User::firstOrCreate(
            ['email' => 'student@school.com'],
            [
                'name' => 'فاطمة علي الطالبة',
                'password' => Hash::make('password123'),
                'role' => User::STUDENT_ROLE,
                'phone' => '01222222222',
                'address' => 'جدة، المملكة العربية السعودية',
                'gender' => 'female',
                'date_of_birth' => '2005-08-20',
            ]
        );

        // إنشاء ملف الطالب
        $student = Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'student_id' => 'S001',
                'phone' => $studentUser->phone,
                'address' => $studentUser->address,
                'gender' => $studentUser->gender,
                'date_of_birth' => $studentUser->date_of_birth,
                'parent_name' => 'علي محمد الوالد',
                'parent_phone' => '01555555555',
                'academic_year' => '2024-2025',
                'enrollment_date' => now()->subMonths(8),
                'major' => 'علوم عامة',
                'class_level' => 'high',
            ]
        );

        // إنشاء مستخدمين إضافيين للاختبار

        // معلم ثاني
        $teacher2User = User::firstOrCreate(
            ['email' => 'teacher2@school.com'],
            [
                'name' => 'سارة أحمد معلمة اللغة العربية',
                'password' => Hash::make('password123'),
                'role' => User::TEACHER_ROLE,
                'phone' => '01333333333',
                'address' => 'الدمام، المملكة العربية السعودية',
                'gender' => 'female',
                'date_of_birth' => '1988-03-10',
                'hire_date' => now()->subYear(),
            ]
        );

        Teacher::firstOrCreate(
            ['user_id' => $teacher2User->id],
            [
                'teacher_id' => 'T002',
                'phone' => $teacher2User->phone,
                'address' => $teacher2User->address,
                'gender' => $teacher2User->gender,
                'date_of_birth' => $teacher2User->date_of_birth,
                'department' => 'اللغة العربية',
                'specialization' => 'اللغة العربية والأدب',
                'hire_date' => $teacher2User->hire_date,
                'salary' => 4800.00,
            ]
        );

        // طالب ثاني
        $student2User = User::firstOrCreate(
            ['email' => 'student2@school.com'],
            [
                'name' => 'محمد خالد الطالب',
                'password' => Hash::make('password123'),
                'role' => User::STUDENT_ROLE,
                'phone' => '01444444444',
                'address' => 'مكة المكرمة، المملكة العربية السعودية',
                'gender' => 'male',
                'date_of_birth' => '2006-12-05',
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $student2User->id],
            [
                'student_id' => 'S002',
                'phone' => $student2User->phone,
                'address' => $student2User->address,
                'gender' => $student2User->gender,
                'date_of_birth' => $student2User->date_of_birth,
                'parent_name' => 'خالد أحمد الوالد',
                'parent_phone' => '01666666666',
                'academic_year' => '2024-2025',
                'enrollment_date' => now()->subMonths(6),
                'major' => 'أدبي',
                'class_level' => 'high',
            ]
        );

        $this->command->info('تم إنشاء المستخدمين التجريبيين بنجاح:');
        $this->command->info('- إدارة: admin@school.com');
        $this->command->info('- معلم: teacher@school.com');
        $this->command->info('- معلم: teacher2@school.com');
        $this->command->info('- طالب: student@school.com');
        $this->command->info('- طالب: student2@school.com');
        $this->command->info('كلمة المرور لجميع الحسابات: password123');
    }
}
