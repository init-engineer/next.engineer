<?php

namespace App\Http\Requests\Frontend\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:100'],
            'email' => [
                Rule::requiredIf(function () {
                    return config('template.access.user.change_email');
                }),
                'max:255',
                'email',
                Rule::unique('users')->ignore($this->user()->id),
            ],
        ];
    }
}
