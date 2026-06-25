<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'friend_id',
    ];
}
