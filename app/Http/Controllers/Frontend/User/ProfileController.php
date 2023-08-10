<?php

namespace App\Http\Controllers\Frontend\User;

use App\Domains\Auth\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use Illuminate\Routing\Redirector;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request, UserRepository $repository): Redirector
    {
        $repository->updateByPrimary($request->user()->id, $request->validated());

        if (session()->has('resent')) {
            return redirect()
                ->route('frontend.auth.verification.notice')
                ->with('flash_info', __('You must confirm your new e-mail address before you can go any further.'));
        }

        return redirect()
            ->route('frontend.user.account', ['#information'])
            ->with('flash_success', __('Profile successfully updated.'));
    }
}
