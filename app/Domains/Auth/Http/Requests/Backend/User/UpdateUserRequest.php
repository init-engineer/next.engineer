<?php

namespace App\Domains\Auth\Http\Requests\Backend\User;

use App\Domains\Auth\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ! ($this->user->isMasterAdmin() && ! $this->user()->isMasterAdmin());
    }

    public function rules(): array
    {
        return [
            'type' => [Rule::requiredIf(function () {
                return ! $this->user->isMasterAdmin();
            }), Rule::in([
                User::TYPE_ADMIN,
                User::TYPE_USER,
            ])],
            'name' => ['required', 'max:100'],
            'email' => ['required', 'max:255', 'email', Rule::unique('users')->ignore($this->user->id)],
            'roles' => ['sometimes', 'array'],
            'roles.*' => [Rule::exists('roles', 'id')->where('type', $this->type)],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => [Rule::exists('permissions', 'id')->where('type', $this->type)],
        ];
    }

    public function messages(): array
    {
        return [
            'roles.*.exists' => __('One or more roles were not found or are not allowed to be associated with this user type.'),
            'permissions.*.exists' => __('One or more permissions were not found or are not allowed to be associated with this user type.'),
        ];
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException(__('Only the administrator can update this user.'));
    }
}
