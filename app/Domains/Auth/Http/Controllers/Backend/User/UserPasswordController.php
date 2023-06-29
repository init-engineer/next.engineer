<?php

namespace App\Domains\Auth\Http\Controllers\Backend\User;

use App\Domains\Auth\Http\Requests\Backend\User\EditUserPasswordRequest;
use App\Domains\Auth\Http\Requests\Backend\User\UpdateUserPasswordRequest;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class UserPasswordController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function edit(EditUserPasswordRequest $request, User $user): Factory|View
    {
        return view('backend.auth.user.change-password')
            ->with('user', $user);
    }

    public function update(UpdateUserPasswordRequest $request, User $user): Redirector|RedirectResponse
    {
        $this->userRepository->updatePassword($user, $request->validated());

        return redirect()
            ->route('admin.auth.user.index')
            ->withFlashSuccess(__('The user\'s password was successfully updated.'));
    }
}
