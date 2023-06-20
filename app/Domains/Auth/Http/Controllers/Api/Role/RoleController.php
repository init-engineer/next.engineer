<?php

namespace App\Domains\Auth\Http\Controllers\Api\User;

use App\Domains\Auth\Http\Requests\Frontend\Role\ManageRoleRequest;
use App\Domains\Auth\Http\Resources\RoleResource;
use App\Domains\Auth\Models\User;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index(ManageRoleRequest $request)
    {
        $user = User::findOrFail($request->validated()['id']);
        $resource = new RoleResource($user);

        return response()->json($resource);
    }
}
