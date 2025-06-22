<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التأكد من وجود الـ roles
        $roles = ['admin', 'teacher', 'student'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // تعيين الـ roles للمستخدمين حسب user_type
        $users = User::all();

        foreach ($users as $user) {
            // إزالة جميع الـ roles الموجودة
            $user->syncRoles([]);

            // تعيين الـ role المطلوب حسب user_type
            switch ($user->user_type) {
                case 'admin':
                    $user->assignRole('admin');
                    break;
                case 'teacher':
                    $user->assignRole('teacher');
                    break;
                case 'student':
                    $user->assignRole('student');
                    break;
            }

            echo "تم تعيين role '{$user->user_type}' للمستخدم: {$user->name}\n";
        }

        echo "تم تعيين الـ roles بنجاح لجميع المستخدمين!\n";
    }
}
