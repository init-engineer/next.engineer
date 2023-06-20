<?php

namespace App\Domains\Auth\Http\Controllers\Backend\Role;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Repositories\PermissionRepository;
use App\Domains\Auth\Repositories\RoleRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class RoleController extends Controller
{
    protected RoleRepository $roleRepository;

    protected PermissionRepository $permissionRepository;

    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(): Factory|View
    {
        return view('backend.auth.role.index');
    }

    public function create(): Factory|View
    {
        return view('backend.auth.role.create')
            ->with('categories', $this->permissionRepository->getCategorizedPermissions())
            ->with('general', $this->permissionRepository->getUncategorizedPermissions());
    }

    public function store(StoreRoleRequest $request): Redirector|RedirectResponse
    {
        $this->roleRepository->store($request->validated());

        return redirect()
            ->route('admin.auth.role.index')
            ->withFlashSuccess(__('The role was successfully created.'));
    }

    public function edit(EditRoleRequest $request, Role $role): Redirector|RedirectResponse
    {
        return view('backend.auth.role.edit')
            ->with('categories', $this->permissionRepository->getCategorizedPermissions())
            ->with('general', $this->permissionRepository->getUncategorizedPermissions())
            ->with('role', $role)
            ->with('usedPermissions', $role->permissions->modelKeys());
    }

    public function update(UpdateRoleRequest $request, Role $role): Redirector|RedirectResponse
    {
        $this->roleRepository->update($role, $request->validated());

        return redirect()
            ->route('admin.auth.role.index')
            ->withFlashSuccess(__('The role was successfully updated.'));
    }

    public function destroy(DeleteRoleRequest $request, Role $role): Redirector|RedirectResponse
    {
        $this->roleRepository->destroy($role);

        return redirect()
            ->route('admin.auth.role.index')
            ->withFlashSuccess(__('The role was successfully deleted.'));
    }
}
