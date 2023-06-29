<?php

namespace App\Domains\Auth\Http\Requests\Backend\Role;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class EditRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !$this->role->isAdmin();
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException(__('You can not edit the Administrator role.'));
    }
}
