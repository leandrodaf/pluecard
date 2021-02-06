<?php

namespace App\Models\Payment;

use App\Models\Model;

class Payer extends Model
{
    protected $table = 'payments_payers';

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
}
