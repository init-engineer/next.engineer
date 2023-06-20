<?php

use Carbon\Carbon;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     */
    function appName(): string
    {
        return config('app.name', '純靠北工程師');
    }
}

if (! function_exists('appUrl')) {
    /**
     * Helper to grab the application url.
     */
    function appUrl(): string
    {
        return config('app.url', 'http://localhost');
    }
}

if (! function_exists('carbon')) {
    /**
     * Create a new Carbon instance from a time.
     */
    function carbon($time): Carbon
    {
        return new Carbon($time);
    }
}

if (! function_exists('homeRoute')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     */
    function homeRoute(): string
    {
        if (auth()->check()) {
            /** @var User $user */
            $user = auth()->user();

            if ($user->isAdmin()) {
                return 'admin.dashboard';
            }

            if ($user->isUser()) {
                return 'frontend.user.dashboard';
            }
        }

        return 'frontend.index';
    }
}
