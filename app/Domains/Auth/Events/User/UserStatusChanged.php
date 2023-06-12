<?php

namespace App\Domains\Auth\Events\User;

use App\Domains\Auth\Models\User;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged
{
    use SerializesModels;

    public User $user;

    public bool $status;

    public function __construct(User $user, bool $status)
    {
        $this->user = $user;
        $this->status = $status;
    }
}
