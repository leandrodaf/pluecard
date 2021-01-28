<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResetPassword extends Model
{
    protected $table = 'resetPasswords';

    protected $fillable = [
        'userId',
        'hash',
        'validatedAt',
        'enable',
    ];

    protected $casts = [
        'hash' => 'string',
        'enable' => 'boolean',
        'validatedAt' => 'date',
    ];

    protected $hidden = ['hash'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}