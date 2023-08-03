<?php

namespace App\Domains\Auth\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class PasswordExpires
{
    /**
     * @return \Illuminate\Http\RedirectResponse|mixed
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_numeric(config('boilerplate.access.user.password_expires_days'))) {
            $password_changed_at = new Carbon($request->user()->password_changed_at ?? $request->user()->created_at);

            if (now()->diffInDays($password_changed_at) >= config('boilerplate.access.user.password_expires_days')) {
                return redirect()
                    ->route('frontend.auth.password.expired')
                    ->withFlashWarning(__('Your password has expired. We require you to change your password every :days days for security purposes.', [
                        'days' => config('boilerplate.access.user.password_expires_days'),
                    ]));
            }
        }

        return $next($request);
    }
}
