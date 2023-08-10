<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class TwoFactorAuthenticationController extends Controller
{
    public function create(Request $request): View|Factory
    {
        $secret = $request->user()->createTwoFactorAuth();

        return view('frontend.user.account.tabs.two-factor-authentication.enable')
            ->with('qrCode', $secret->toQr())
            ->with('secret', $secret->toString());
    }

    public function show(Request $request): View|Factory
    {
        return view('frontend.user.account.tabs.two-factor-authentication.recovery')
            ->with('recoveryCodes', $request->user()->getRecoveryCodes());
    }

    public function update(Request $request): Redirector
    {
        $request->user()->generateRecoveryCodes();

        session()->flash('flash_warning', __('Any old backup codes have been invalidated.'));

        return redirect()
            ->route('frontend.auth.account.2fa.show')
            ->with('flash_success', __('Two Factor Recovery Codes Regenerated'));
    }
}
