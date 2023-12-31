<?php

namespace App\Providers;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        //
    }
}
