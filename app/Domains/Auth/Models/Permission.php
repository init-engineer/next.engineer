<?php

namespace App\Domains\Auth\Models;

use App\Domains\Auth\Models\Traits\Relationship\PermissionRelationship;
use App\Domains\Auth\Models\Traits\Scope\PermissionScope;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use PermissionRelationship,
        PermissionScope;
}
