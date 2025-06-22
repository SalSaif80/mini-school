<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BasicUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'user_type' => User::ADMIN,
        ]);
        $admin->assignRole('admin');

        // Create Sample Teacher
        $teacher = User::create([
            'name' => 'Ahmed Mohamed',
            'username' => 'teacher1',
            'password' => Hash::make('teacher123'),
            'user_type' => User::TEACHER,
        ]);
        $teacher->assignRole('teacher');

        // Create Sample Student
        $student = User::create([
            'name' => 'Sara Ali',
            'username' => 'student1',
            'password' => Hash::make('student123'),
            'user_type' => User::STUDENT,
        ]);
        $student->assignRole('student');

        // Create another Student
        $student2 = User::create([
            'name' => 'Omar Hassan',
            'username' => 'student2',
            'password' => Hash::make('student123'),
            'user_type' => User::STUDENT,
        ]);
        $student2->assignRole('student');
    }
}
