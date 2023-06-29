<?php

namespace App\Domains\Auth\Http\Controllers\Backend\User;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class DeactivatedUserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): Factory|View
    {
        return view('backend.auth.user.deactivated');
    }

    public function update(Request $request, User $user, int $status): Redirector|RedirectResponse
    {
        $this->userRepository->mark($user, $status);

        return redirect()->route(
            $status === 1 || ! $request->user()->can('admin.access.user.reactivate') ?
                'admin.auth.user.index' :
                'admin.auth.user.deactivated'
        )->withFlashSuccess(__('The user was successfully updated.'));
    }
}
