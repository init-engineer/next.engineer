<?php

namespace App\Domains\Auth\Http\Controllers\Backend\User;

use App\Domains\Auth\Http\Requests\Backend\User\ClearUserSessionRequest;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class UserSessionController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(ClearUserSessionRequest $request, User $user): Redirector|RedirectResponse
    {
        $this->userRepository->updateByPrimary($user->id, [
            'to_be_logged_out' => true,
        ]);

        return redirect()
            ->back()
            ->withFlashSuccess(__('The user\'s session was successfully cleared.'));
    }
}
