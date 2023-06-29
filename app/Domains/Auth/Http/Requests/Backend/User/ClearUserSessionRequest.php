<?php

namespace App\Domains\Auth\Http\Requests\Backend\User;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ClearUserSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user->id !== $this->user()->id;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException(__('You can not clear your own session.'));
    }
}
