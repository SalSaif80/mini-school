<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('11111111'),
            'date_of_birth' => fake()->dateTimeBetween('1980-01-01', '1990-12-31')->format('Y-m-d'),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
        ]);
        $role = Role::where('name', User::SUPER_ADMIN_ROLE)->first();
        $permissions = Permission::pluck('name')->all();
        $role->syncPermissions($permissions);
        $user->assignRole($role->name);
    }
}
