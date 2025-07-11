<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = User::availableRoles();
        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }

    }
}
