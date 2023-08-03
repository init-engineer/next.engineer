<?php

namespace App\Domains\Auth\Http\Requests\Backend\User;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ! ($this->user->isMasterAdmin() && ! $this->user()->isMasterAdmin());
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException(__('Only the administrator can update this user.'));
    }
}
