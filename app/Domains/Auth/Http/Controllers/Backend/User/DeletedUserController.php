<?php

namespace App\Domains\Auth\Http\Controllers\Backend\User;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class DeletedUserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): Factory|View
    {
        return view('backend.auth.user.deleted');
    }

    public function update(User $deletedUser): Redirector|RedirectResponse
    {
        $this->userService->restore($deletedUser);

        return redirect()
            ->route('admin.auth.user.index')
            ->withFlashSuccess(__('The user was successfully restored.'));
    }

    public function destroy(User $deletedUser): Redirector|RedirectResponse
    {
        abort_unless(config('boilerplate.access.user.permanently_delete'), 404);

        $this->userService->destroy($deletedUser);

        return redirect()
            ->route('admin.auth.user.deleted')
            ->withFlashSuccess(__('The user was permanently deleted.'));
    }
}
