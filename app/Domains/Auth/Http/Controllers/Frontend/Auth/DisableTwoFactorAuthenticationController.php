<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Http\Requests\Frontend\Auth\DisableTwoFactorAuthenticationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class DisableTwoFactorAuthenticationController extends Controller
{
    public function show(): View|Factory
    {
        return view('frontend.user.account.tabs.two-factor-authentication.disable');
    }

    public function destroy(DisableTwoFactorAuthenticationRequest $request): Redirector
    {
        $request->user()->disableTwoFactorAuth();

        return redirect()
            ->route('frontend.user.account', [
                '#two-factor-authentication',
            ])
            ->withFlashSuccess(__('Two Factor Authentication Successfully Disabled'));
    }
}
