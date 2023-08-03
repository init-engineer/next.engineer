<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Http\Requests\Frontend\Auth\UpdatePasswordRequest;
use App\Domains\Auth\Repositories\UserRepository;
use App\Http\Controllers\Controller;

class UpdatePasswordController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(UpdatePasswordRequest $request)
    {
        $this->userRepository->updateByPrimary($request->user()->id, $request->validated());

        return redirect()
            ->route('frontend.user.account', [
                '#password',
            ])
            ->withFlashSuccess(__('Password successfully updated.'));
    }
}
