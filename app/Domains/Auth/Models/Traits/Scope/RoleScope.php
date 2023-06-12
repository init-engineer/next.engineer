<?php

namespace App\Domains\Auth\Models\Traits\Scope;

use Illuminate\Database\Eloquent\Builder;

trait RoleScope
{
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($query) use ($term) {
            $query->where('name', 'like', '%' . $term . '%')
                ->orWhereHas('permissions', function ($query) use ($term) {
                    $query->where('description', 'like', '%' . $term . '%');
                });
        });
    }
}
