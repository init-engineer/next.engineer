<?php

namespace App\Domains\Auth\Models\Traits\Relationship;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait PermissionRelationship
{

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id')->with('parent');
    }

    public function children(): BelongsTo
    {
        return $this->hasMany(__CLASS__, 'parent_id')->with('children');
    }
}
