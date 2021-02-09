<?php

namespace App\Models\Payment;

use App\Models\Model;

class PaymentTransactions extends Model
{
    protected $table = 'payments_transactions';

    protected $fillable = [
        'currency_id',
        'amount',
        'quantity',
        'status',
        'installments',
        'user_id',
        'payments_item_id',
        'payment_id',
        'payments_card_id',
        'payments_payer_id',
    ];

    protected $casts = [
        'currency_id' => 'string',
        'amount' => 'float',
        'installments' => 'integer',
        'quantity' => 'integer',
        'status' => 'string',
    ];
}
