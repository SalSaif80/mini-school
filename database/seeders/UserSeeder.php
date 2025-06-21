<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@school.com',
            'password' => Hash::make('password123'),
            'role' => User::SUPER_ADMIN_ROLE,
            'email_verified_at' => now(),
            'date_of_birth' => fake()->dateTimeBetween('1980-01-01', '1990-12-31')->format('Y-m-d'),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'hire_date' => fake()->dateTimeBetween('2015-01-01', '2023-12-31')->format('Y-m-d'),
        ]);
        $role = Role::where('name', User::SUPER_ADMIN_ROLE)->first();
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $admin->assignRole([$role->id]);

        // Create Teachers
        $teachers = [
            [
                'name' => 'أحمد محمود',
                'email' => 'ahmed.mahmoud@school.com',
                'teacher_id' => 'T001',
                'department' => 'الرياضيات',
                'specialization' => 'الجبر والهندسة',
                'salary' => 5000.00,
            ],
            [
                'name' => 'فاطمة الزهراء',
                'email' => 'fatima.zahra@school.com',
                'teacher_id' => 'T002',
                'department' => 'اللغة العربية',
                'specialization' => 'النحو والصرف',
                'salary' => 4800.00,
            ],
            [
                'name' => 'محمد العلي',
                'email' => 'mohammed.ali@school.com',
                'teacher_id' => 'T003',
                'department' => 'العلوم',
                'specialization' => 'الفيزياء والكيمياء',
                'salary' => 5200.00,
            ],
            [
                'name' => 'نورا سالم',
                'email' => 'nora.salem@school.com',
                'teacher_id' => 'T004',
                'department' => 'التاريخ',
                'specialization' => 'التاريخ الإسلامي',
                'salary' => 4700.00,
            ],
        ];

        foreach ($teachers as $teacherData) {
            $user = User::create([
                'name' => $teacherData['name'],
                'email' => $teacherData['email'],
                'password' => Hash::make('password123'),
                'role' => User::TEACHER_ROLE,
                'email_verified_at' => now(),
                'date_of_birth' => fake()->dateTimeBetween('1980-01-01', '1990-12-31')->format('Y-m-d'),
                'gender' => fake()->randomElement(['male', 'female']),
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                'hire_date' => fake()->dateTimeBetween('2015-01-01', '2023-12-31')->format('Y-m-d'),
            ]);
            $role = Role::where('name', User::TEACHER_ROLE)->first();
            $permissions = Permission::pluck('id', 'id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);

            Teacher::create([
                'user_id' => $user->id,
                'teacher_id' => $teacherData['teacher_id'],
                'date_of_birth' => fake()->dateTimeBetween('1980-01-01', '1990-12-31')->format('Y-m-d'),
                'gender' => fake()->randomElement(['male', 'female']),
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                'hire_date' => fake()->dateTimeBetween('2015-01-01', '2023-12-31')->format('Y-m-d'),
                'department' => $teacherData['department'],
                'specialization' => $teacherData['specialization'],
                'salary' => $teacherData['salary'],
            ]);
           
        }

        // Create Students
        $students = [
            ['name' => 'علي أحمد', 'email' => 'ali.ahmed@student.com', 'student_id' => 'S001', 'major' => 'علوم الحاسوب', 'class_level' => 'الصف الأول'],
            ['name' => 'مريم محمد', 'email' => 'mariam.mohammed@student.com', 'student_id' => 'S002', 'major' => 'الرياضيات', 'class_level' => 'الصف الثاني'],
            ['name' => 'سارة خالد', 'email' => 'sara.khaled@student.com', 'student_id' => 'S003', 'major' => 'الفيزياء', 'class_level' => 'الصف الأول'],
            ['name' => 'محمد عبدالله', 'email' => 'mohammed.abdullah@student.com', 'student_id' => 'S004', 'major' => 'الكيمياء', 'class_level' => 'الصف الثالث'],
            ['name' => 'فاطمة علي', 'email' => 'fatima.ali@student.com', 'student_id' => 'S005', 'major' => 'الأحياء', 'class_level' => 'الصف الثاني'],
            ['name' => 'عمر حسن', 'email' => 'omar.hassan@student.com', 'student_id' => 'S006', 'major' => 'التاريخ', 'class_level' => 'الصف الأول'],
            ['name' => 'نور الدين', 'email' => 'nour.eldeen@student.com', 'student_id' => 'S007', 'major' => 'اللغة العربية', 'class_level' => 'الصف الثالث'],
            ['name' => 'آية محمود', 'email' => 'aya.mahmoud@student.com', 'student_id' => 'S008', 'major' => 'الجغرافيا', 'class_level' => 'الصف الثاني'],
        ];

        foreach ($students as $studentData) {
            $user = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password123'),
                'role' => User::STUDENT_ROLE,
                'email_verified_at' => now(),
                'date_of_birth' => fake()->dateTimeBetween('2000-01-01', '2005-12-31')->format('Y-m-d'),
                'gender' => fake()->randomElement(['male', 'female']),
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
            ]);
            $role = Role::where('name', User::STUDENT_ROLE)->first();
            $permissions = Permission::pluck('id', 'id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);

            Student::create([
                'user_id' => $user->id,
                'student_id' => $studentData['student_id'],
                'enrollment_date' => fake()->dateTimeBetween('2020-01-01', '2024-12-31')->format('Y-m-d'),
                'major' => $studentData['major'],
                'class_level' => $studentData['class_level'],
            ]);

        }
    }
}
