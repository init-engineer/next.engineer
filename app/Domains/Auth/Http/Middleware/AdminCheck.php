<?php

namespace App\Domains\Auth\Http\Middleware;

use App\Domains\Auth\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AdminCheck
{
    public function handle(Request $request, Closure $next): Closure|Redirector|RedirectResponse
    {
        if ($request->user() && $request->user()->isType(User::TYPE_ADMIN)) {
            return $next($request);
        }

        return redirect()
            ->route('frontend.index')
            ->withFlashDanger(__('You do not have access to do that.'));
    }
}