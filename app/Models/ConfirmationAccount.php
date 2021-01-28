<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfirmationAccount extends Model
{
    protected $table = 'confirmationAccounts';

    protected $fillable = [
        'userId',
        'hash',
        'validatedAt',
    ];

    protected $casts = [
        'hash' => 'string',
    ];

    protected $hidden = ['hash'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
