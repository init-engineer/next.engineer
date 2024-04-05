<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
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
        ]);

        if (app()->environment(['local', 'testing'])) {
            User::create([
                'name' => 'Test User',
                'email' => 'user@user.com',
                'password' => 'secret',
            ]);
        }
    }
}
