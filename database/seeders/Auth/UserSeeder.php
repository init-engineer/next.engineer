<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => 'secret',
        ])->assignRole('admin.access.users.management');

        if (app()->environment(['local', 'testing'])) {
            User::create([
                'name' => 'Test User',
                'email' => 'user@user.com',
                'password' => 'secret',
            ]);
        }
    }
}
