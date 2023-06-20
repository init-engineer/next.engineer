<?php

namespace App\Domains\Auth\Http\Controllers\Api\User;

use App\Domains\Auth\Http\Requests\Frontend\User\ManageUserRequest;
use App\Domains\Auth\Http\Resources\UserResource;
use App\Domains\Auth\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(ManageUserRequest $request)
    {
        $user = User::findOrFail($request->validated()['id']);
        $resource = new UserResource($user);

        return response()->json($resource);
    }
}
