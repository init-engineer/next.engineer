<?php

namespace App\Domains\Auth\Models\Traits\Method;

use Illuminate\Support\Collection;

trait RoleMethod
{
    public function isAdmin(): bool
    {
        return $this->name === config('template.access.role.admin');
    }

    public function getPermissionDescriptions(): Collection
    {
        return $this->permissions->pluck('description');
    }
}
