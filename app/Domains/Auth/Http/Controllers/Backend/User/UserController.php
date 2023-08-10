<?php

namespace App\Domains\Auth\Http\Controllers\Backend\User;

use App\Domains\Auth\Events\User\UserCreated;
use App\Domains\Auth\Events\User\UserDeleted;
use App\Domains\Auth\Events\User\UserUpdated;
use App\Domains\Auth\Http\Requests\Backend\User\DeleteUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\EditUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\StoreUserRequest;
use App\Domains\Auth\Http\Requests\Backend\User\UpdateUserRequest;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Repositories\PermissionRepository;
use App\Domains\Auth\Repositories\RoleRepository;
use App\Domains\Auth\Repositories\UserRepository;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    protected RoleRepository $roleRepository;

    protected PermissionRepository $permissionRepository;

    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(): Factory|View
    {
        return view('backend.auth.user.index');
    }

    public function create(): Factory|View
    {
        return view('backend.auth.user.create')
            ->with('roles', $this->roleRepository->getAll())
            ->with('categories', $this->permissionRepository->getCategorizedPermissions())
            ->with('general', $this->permissionRepository->getUncategorizedPermissions());
    }

    public function store(StoreUserRequest $request): Redirector|RedirectResponse
    {
        $user = $this->userRepository->createOrUpdateFromArray($request->validated());

        event(new UserCreated($user));

        return redirect()
            ->route('admin.auth.user.show', $user)
            ->with('flash_success', __('The user was successfully created.'));
    }

    public function show(User $user): Factory|View
    {
        return view('backend.auth.user.show')
            ->with('user', $user);
    }

    public function edit(EditUserRequest $request, User $user): Factory|View
    {
        return view('backend.auth.user.edit')
            ->with('user', $user)
            ->with('roles', $this->roleRepository->getAll())
            ->with('categories', $this->permissionRepository->getCategorizedPermissions())
            ->with('general', $this->permissionRepository->getUncategorizedPermissions())
            ->with('usedPermissions', $user->permissions->modelKeys());
    }

    public function update(UpdateUserRequest $request, User $user): Redirector|RedirectResponse
    {
        $user = $this->userRepository->updateByPrimary($user->id, $request->validated(), false);

        event(new UserUpdated($user));

        return redirect()
            ->route('admin.auth.user.show', $user)
            ->with('flash_success', __('The user was successfully updated.'));
    }

    public function destroy(DeleteUserRequest $request, User $user): Redirector|RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            throw new GeneralException(__('You can not delete yourself.'));
        }

        $this->userRepository->deleteByPrimary($user->id);

        event(new UserDeleted($user));

        return redirect()
            ->route('admin.auth.user.deleted')
            ->with('flash_success', __('The user was successfully deleted.'));
    }
}
