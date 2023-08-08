<?php

namespace App\Domains\Auth\Repositories;

use App\Domains\Auth\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    protected $model = Role::class;

    /**
     * Create or update a model instance from data array.
     * The use of this method suppose that your array is correctly formatted.
     */
    public function createOrUpdateFromArray(
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): Role {
        $role = parent::createOrUpdateFromArray($data, $saveMissingModelFillableAttributesToNull);
        $role->syncPermissions($data['permissions'] ?? []);

        return $role;
    }

    /**
     * Update a model instance from its primary key.
     */
    public function updateByPrimary(
        int $primary,
        array $data,
        bool $saveMissingModelFillableAttributesToNull = true
    ): Role {
        $role = parent::updateByPrimary($primary, $data, $saveMissingModelFillableAttributesToNull);
        $role->syncPermissions($data['permissions'] ?? []);

        return $role;
    }
}
