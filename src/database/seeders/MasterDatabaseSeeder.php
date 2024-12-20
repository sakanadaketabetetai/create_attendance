<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class MasterDatabaseSeeder extends Seeder
{
    public function run()
    {
        $admin = Admin::create([
            'name' => '管理者',
            'email' => 'admin@sample.com',
            'password' => Hash::make('admin12345'),
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name' => '使用者',
            'email' => 'user@sample.com',
            'password' => Hash::make('user12345'),
            'email_verified_at' => now(),
        ]);

        $adminPermissions = ['management-user'];
        $userPermissions = ['view-attendance-info'];

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }
        foreach ($userPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $adminRole->givePermissionTo($adminPermissions);
        $userRole->givePermissionTo($userPermissions);

        $admin->assignRole($adminRole);
        $user->assignRole($userRole);
    }
}
