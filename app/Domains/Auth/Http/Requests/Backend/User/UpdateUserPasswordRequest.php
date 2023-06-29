<?php

namespace App\Domains\Auth\Http\Requests\Backend\User;

use App\Domains\Auth\Rules\UnusedPassword;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

class UpdateUserPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !($this->user->isMasterAdmin() && !$this->user()->isMasterAdmin());
    }

    public function rules(): array
    {
        return [
            'password' => array_merge(
                [
                    'max:100',
                    new UnusedPassword((int) $this->segment(4)),
                ],
                PasswordRules::changePassword($this->email)
            ),
        ];
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException(__('Only the administrator can change their password.'));
    }
}
