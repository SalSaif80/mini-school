<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionTableSeeder::class,
            RoleTableSeeder::class,
            CreateAdminUserSeeder::class,
            // UserSeeder::class,
            TestUsersSeeder::class,
            CourseSeeder::class,
            EnrollmentSeeder::class,
        ]);
    }
}
