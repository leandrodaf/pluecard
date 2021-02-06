<?php

namespace App\Models\Payment;

use App\Models\Model;

class Card extends Model
{
    protected $table = 'payments_cards';

    protected $fillable = [
        'external_id',
        'customer_id',
        'expiration_month',
        'expiration_year',
        'first_six_digits',
        'last_four_digits',
        'payment_method',
        'security_code',
        'issuer',
        'cardholder',
        'date_created',
        'date_last_updated',
        'last',
        'error',
        'pagination_params',
        'empty',
        'user_id',
        'payment_id',
    ];

    protected $casts = [
        'external_id' => 'integer',
        'customer_id' => 'string',
        'expiration_month' => 'integer',
        'expiration_year' => 'integer',
        'first_six_digits' => 'string',
        'last_four_digits' => 'string',
        'payment_method' => 'json',
        'payment_method.id' => 'string',
        'payment_method.name' => 'string',
        'payment_method.payment_type_id' => 'string',
        'payment_method.secure_thumbnail' => 'string',
        'security_code' => 'json',
        'security_code.length' => 'integer',
        'security_code.card_location' => 'string',
        'issuer' => 'json',
        'issuer.id' => 'integer',
        'issuer.name' => 'string',
        'cardholder' => 'json',
        'cardholder.name' => 'integer',
        'cardholder.identification' => 'json',
        'cardholder.identification.number' => 'integer',
        'cardholder.identification.subtype' => 'string',
        'cardholder.identification.type' => 'string',
        'date_created' => 'datetime',
        'date_last_updated' => 'datetime',
    ];
}
