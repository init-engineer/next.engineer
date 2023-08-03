<?php

namespace Tests;

use App\Domains\Auth\Http\Middleware\TwoFactorAuthenticationStatus;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');

        $this->withoutMiddleware(RequirePassword::class);
        $this->withoutMiddleware(TwoFactorAuthenticationStatus::class);
    }

    protected function getAdminRole(): Role
    {
        return Role::find(1);
    }

    protected function getMasterAdmin(): User
    {
        return User::find(1);
    }

    protected function loginAsAdmin($admin = false): User
    {
        if (! $admin) {
            $admin = $this->getMasterAdmin();
        }

        $this->actingAs($admin);

        return $admin;
    }

    protected function logout(): void
    {
        auth()->logout();
    }
}
