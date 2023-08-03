<?php

namespace App\Rules;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\ValidationRule;

class Captcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail(__('The captcha was invalid.'));

            return;
        }

        $response = json_decode((new Client([
            'timeout' => config('boilerplate.access.captcha.configs.options.timeout'),
        ]))->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('boilerplate.access.captcha.configs.secret_key'),
                'remoteip' => request()->getClientIp(),
                'response' => $value,
            ],
        ])->getBody(), true);

        if (! (isset($response['success']) && $response['success'] === true)) {
            $fail(__('The captcha was invalid.'));
        }
    }
}
