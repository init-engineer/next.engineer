<?php

namespace App\Domains\Auth\Rules;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Services\UserService;
use Closure;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\ValidationRule;

class UnusedPassword implements ValidationRule
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Option is off
        if (! config('boilerplate.access.user.password_history')) {
            return;
        }

        if (! $this->user instanceof User) {
            if (is_numeric($this->user)) {
                $this->user = resolve(UserService::class)->getById($this->user);
            } else {
                $this->user = resolve(UserService::class)->getByColumn($this->user, 'email');
            }
        }

        if (! $this->user || null === $this->user) {
            $fail('User not found');
            return;
        }

        $histories = $this->user
            ->passwordHistories()
            ->take(config('boilerplate.access.user.password_history'))
            ->orderBy('id', 'desc')
            ->get();

        foreach ($histories as $history) {
            if (Hash::check($value, $history->password)) {
                $fail(__('You can not set a password that you have previously used within the last :num times.', [
                    'num' => config('boilerplate.access.user.password_history'),
                ]));
                return;
            }
        }
    }
}
