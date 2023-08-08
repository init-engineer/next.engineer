<?php

namespace App\Domains\Auth\Models\Traits\Method;

use App\Domains\Auth\Models\User;
use Illuminate\Support\Collection;

trait UserMethod
{
    public function isMasterAdmin(): bool
    {
        return $this->id === 1;
    }

    public function isAdmin(): bool
    {
        return $this->type === User::TYPE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->type === User::TYPE_USER;
    }

    public function hasAllAccess(): bool
    {
        return $this->isAdmin() && $this->hasRole(config('template.access.role.admin'));
    }

    public function isType($type): bool
    {
        return $this->type === $type;
    }

    public function canChangeEmail(): bool
    {
        return config('template.access.user.change_email');
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function isProvider(): bool
    {
        return $this->provider && $this->provider_id;
    }

    public function getPermissionDescriptions(): Collection
    {
        return $this->permissions->pluck('description');
    }

    public function getAvatar(bool $size = null): string
    {
        return sprintf(
            'https://gravatar.com/avatar/%s?s=%s&d=mp',
            md5(strtolower(trim($this->email))),
            config('template.avatar.size', $size)
        );
    }
}
