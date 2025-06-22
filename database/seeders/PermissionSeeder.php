<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management
            'manage-users',
            'view-users',

            // Course management
            'manage-courses',
            'view-courses',
            'view-own-courses',

            // Enrollment management
            'manage-enrollments',
            'view-enrollments',
            'view-own-enrollments',
            'update-grades',
            'update-own-course-grades',

            // Activity log
            'view-activity-log',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::create(['name' => User::ADMIN]);
        $admin->givePermissionTo(Permission::all());

        $teacher = Role::create(['name' => User::TEACHER]);
        $teacher->givePermissionTo([
            'view-courses',
            'view-own-courses',
            'view-enrollments',
            'update-own-course-grades',
        ]);

        $student = Role::create(['name' => User::STUDENT]);
        $student->givePermissionTo([
            'view-own-enrollments',
        ]);
    }
}
