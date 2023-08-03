<?php

namespace Database\Seeders\Auth;

use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run(): void
    {
        $this->disableForeignKeys();

        User::find(1)->assignRole(config('boilerplate.access.role.admin'));
        User::find(1)->assignRole(config('boilerplate.access.role.social_admin'));
        User::find(1)->assignRole(config('boilerplate.access.role.announcement_admin'));

        $this->enableForeignKeys();
    }
}
