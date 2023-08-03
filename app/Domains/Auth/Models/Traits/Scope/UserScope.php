<?php

namespace App\Domains\Auth\Models\Traits\Scope;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait UserScope
{
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($query) use ($term) {
            $query->where('name', 'like', '%'.$term.'%')
                ->orWhere('email', 'like', '%'.$term.'%');
        });
    }

    public function scopeOnlyDeactivated(Builder $query): Builder
    {
        return $query->whereActive(false);
    }

    public function scopeOnlyActive(Builder $query): Builder
    {
        return $query->whereActive(true);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeAllAccess(Builder $query): Builder
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', config('boilerplate.access.role.admin'));
        });
    }

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('type', User::TYPE_ADMIN);
    }

    public function scopeUsers(Builder $query): Builder
    {
        return $query->where('type', User::TYPE_USER);
    }
}
