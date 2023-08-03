<?php

namespace App\Domains\Auth\Models;

use App\Domains\Auth\Models\Traits\Attribute\RoleAttribute;
use App\Domains\Auth\Models\Traits\Method\RoleMethod;
use App\Domains\Auth\Models\Traits\Scope\RoleScope;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory,
        RoleAttribute,
        RoleMethod,
        RoleScope;

    protected $with = [
        'permissions',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }
}
