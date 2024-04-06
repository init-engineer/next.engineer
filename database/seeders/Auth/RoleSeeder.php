<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = Role::create([
            'name' => 'admin.access.users.management',
            'description' => 'Users Management',
        ]);
        $users->givePermissionTo('admin.access.user');
        $users->givePermissionTo('admin.access.user.list');
        $users->givePermissionTo('admin.access.user.deactivate');
        $users->givePermissionTo('admin.access.user.reactivate');
        $users->givePermissionTo('admin.access.user.clear-session');
        $users->givePermissionTo('admin.access.user.impersonate');
        $users->givePermissionTo('admin.access.user.change-password');
    }
}
