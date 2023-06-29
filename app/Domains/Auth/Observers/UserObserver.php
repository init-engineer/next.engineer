<?php

namespace App\Domains\Auth\Observers;

use App\Domains\Auth\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $this->logPasswordHistory($user);
    }

    public function updated(User $user): void
    {
        // Only log password history on update if the password actually changed
        if ($user->isDirty('password')) {
            $this->logPasswordHistory($user);
        }
    }

    private function logPasswordHistory(User $user): void
    {
        if (config('boilerplate.access.user.password_history')) {
            $user->passwordHistories()->create([
                'password' => $user->password, // Password already hashed & saved so just take from model
            ]);
        }
    }
}
