<?php

namespace App\Domains\Auth\Models\Traits\Relationship;

use App\Domains\Auth\Models\PasswordHistory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait UserRelationship
{
    public function passwordHistories(): MorphMany
    {
        return $this->morphMany(PasswordHistory::class, 'model');
    }
}
