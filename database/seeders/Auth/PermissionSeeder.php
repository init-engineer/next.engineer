<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create([
            'name' => 'admin.access.user',
            'description' => 'All User Permissions',
        ]);
        Permission::create([
            'name' => 'admin.access.user.list',
            'description' => 'View Users',
        ]);
        Permission::create([
            'name' => 'admin.access.user.deactivate',
            'description' => 'Deactivate Users',
        ]);
        Permission::create([
            'name' => 'admin.access.user.reactivate',
            'description' => 'Reactivate Users',
        ]);
        Permission::create([
            'name' => 'admin.access.user.clear-session',
            'description' => 'Clear User Sessions',
        ]);
        Permission::create([
            'name' => 'admin.access.user.impersonate',
            'description' => 'Impersonate Users',
        ]);
        Permission::create([
            'name' => 'admin.access.user.change-password',
            'description' => 'Change User Passwords',
        ]);
    }
}
