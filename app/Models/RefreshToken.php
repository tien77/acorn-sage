<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $table = 'refresh_tokens';
    protected $fillable = [
        'user_id', 'token_hash', 'user_agent', 'ip_address', 'expires_at', 'revoked', 'replaced_by_id',
    ];
    protected $casts = [
        'revoked' => 'bool',
        'expires_at' => 'datetime',
    ];
}
