<?php

namespace App\Domains\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class TwoFactorAuthenticationStatus
{
    public function handle(Request $request, Closure $next, string $status = 'enabled'):  Closure|Redirector|RedirectResponse
    {
        if (!in_array($status, ['enabled', 'disabled'])) {
            abort(404);
        }

        // If the backend does not require 2FA than continue
        if ($status === 'enabled' && $request->is('admin*') && !config('boilerplate.access.user.admin_requires_2fa')) {
            return $next($request);
        }

        // Page requires 2fa, but user is not enabled or page does not require 2fa, but it is enabled
        if (
            ($status === 'enabled' && !$request->user()->hasTwoFactorEnabled()) ||
            ($status === 'disabled' && $request->user()->hasTwoFactorEnabled())
        ) {
            return redirect()
                ->route('frontend.auth.account.2fa.create')
                ->withFlashDanger(__('Two-factor Authentication must be :status to view this page.', ['status' => $status]));
        }

        return $next($request);
    }
}
