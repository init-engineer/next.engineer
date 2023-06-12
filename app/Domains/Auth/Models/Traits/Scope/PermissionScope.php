<?php

namespace App\Domains\Auth\Models\Traits\Scope;

use Illuminate\Database\Eloquent\Builder;

trait PermissionScope
{
    public function scopeIsMaster(Builder $query): Builder
    {
        return $query->whereDoesntHave('parent')
            ->whereHas('children');
    }

    public function scopeIsParent(Builder $query): Builder
    {
        return $query->whereHas('children');
    }

    public function scopeIsChild(Builder $query): Builder
    {
        return $query->whereHas('parent');
    }

    public function scopeSingular(Builder $query): Builder
    {
        return $query->whereNull('parent_id')
            ->whereDoesntHave('children');
    }
}
