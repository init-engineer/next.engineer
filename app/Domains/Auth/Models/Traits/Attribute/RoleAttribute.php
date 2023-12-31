<?php

namespace App\Domains\Auth\Models\Traits\Attribute;

trait RoleAttribute
{
    public function getPermissionsLabelAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'All';
        }

        if (! $this->permissions->count()) {
            return 'None';
        }

        return collect($this->getPermissionDescriptions())
            ->implode('<br/>');
    }
}
