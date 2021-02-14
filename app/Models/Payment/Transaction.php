<?php

namespace App\Models\Payment;

use App\Models\Item;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'payments_transactions';

    protected $fillable = [
        'currency_id',
        'amount',
        'quantity',
        'status',
        'installments',
        'user_id',
        'item_id',
        'payment_id',
        'card_id',
        'payer_id',
    ];

    protected $casts = [
        'currency_id' => 'string',
        'amount' => 'float',
        'installments' => 'integer',
        'quantity' => 'integer',
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(Payer::class, 'payer_id', 'id');
    }
}
