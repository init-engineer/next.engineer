<?php

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table = 'password_histories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'password',
    ];
}
