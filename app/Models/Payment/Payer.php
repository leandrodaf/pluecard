<?php

namespace App\Models\Payment;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payer extends Model
{
    protected $table = 'payers';

    protected $fillable = [
        'external_id',
        'type',
        'name',
        'surname',
        'first_name',
        'last_name',
        'email',
        'date_created',
        'phone',
        'identification',
        'address',
        'user_id',
        'payment_id',
    ];

    protected $casts = [
        'external_id' => 'integer',
        'type' => 'string',
        'name' => 'string',
        'surname' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'date_created' => 'datetime',

        'phone' => 'json',
        'phone.area_code' => 'string',
        'phone.number' => 'string',

        'identification' => 'json',
        'identification.type' => 'string',
        'identification.number' => 'string',

        'address' => 'json',
        'address.zip_code' => 'string',
        'address.street_name' => 'string',
        'address.street_number' => 'integer',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
