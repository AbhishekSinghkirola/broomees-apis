<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'token_hash',
        'expires_at',
        'is_revoked'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_revoked' => 'boolean'
    ];
}
