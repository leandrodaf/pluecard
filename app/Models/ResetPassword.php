<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResetPassword extends Model
{
    protected $table = 'reset_passwords';

    protected $fillable = [
        'user_id',
        'hash',
        'validated_at',
        'enable',
    ];

    protected $casts = [
        'hash' => 'string',
        'enable' => 'boolean',
        'validated_at' => 'date',
    ];

    protected $hidden = ['hash'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
