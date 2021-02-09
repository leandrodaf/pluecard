<?php

namespace App\Models\Payment;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'external_id',
        'operation_type',
        'order',
        'binary_mode',
        'external_reference',
        'status',
        'status_detail',
        'date_created',
        'last_modified',
        'live_mode',
        'date_last_updated',
        'date_of_expiration',
        'date_approved',
        'money_release_date',
        'currency_id',
        'transaction_amount',
        'transaction_amount_refunded',
        'collector_id',
        'payment_method_id',
        'payment_type_id',
        'transaction_details',
        'fee_details',
        'differential_pricing_id',
        'application_fee',
        'capture',
        'captured',
        'call_for_authorize_id',
        'statement_descriptor',
        'refunds',
        'additional_info',
        'campaign_id',
        'coupon_amount',
        'installments',
        'token',
        'description',
        'notification_url',
        'issuer_id',
        'metadata',
        'callback_url',
        'coupon_code',
        'user_id',
    ];

    protected $casts = [
        'external_id' => 'string',
        'operation_type' => 'string', // regular_payment, money_transfer, recurring_payment, account_fund, payment_addition, cellphone_recharge, pos_payment

        'order' => 'json',
        'order.type' => 'string', // mercadolibre,mercadopago
        'order.id' => 'string',

        'external_reference' => 'string',
        'status' => 'string', // pending, approved, authorized, in_process, in_mediation, rejected, cancelled, refunded, charged_back
        'status_detail' => 'string',
        'date_created' => 'datetime',
        'last_modified' => 'datetime',
        'live_mode' => 'boolean',
        'date_last_updated' => 'datetime',
        'date_of_expiration' => 'datetime',
        'date_approved' => 'datetime',
        'money_release_date' => 'datetime',
        'currency_id' => 'string', // ARS, BRL, CLP, MXN, COP, PEN, UYU
        'transaction_amount' => 'float',
        'transaction_amount_refunded' => 'float',
        'collector_id' => 'integer',
        'payment_method_id' => 'string',
        'payment_type_id' => 'string', //account_money, ticket, bank_transfer, atm, credit_card, debit_card, prepaid_card

        'transaction_details' => 'json',
        'transaction_details.financial_institution' => 'string',
        'transaction_details.net_received_amount' => 'float',
        'transaction_details.total_paid_amount' => 'float',
        'transaction_details.installment_amount' => 'float',
        'transaction_details.overpaid_amount' => 'float',
        'transaction_details.external_resource_url' => 'string',
        'transaction_details.payment_method_reference_id' => 'string',

        'fee_details' => 'json',
        'differential_pricing_id' => 'integer',
        'application_fee' => 'float',
        'capture' => 'boolean',
        'captured' => 'boolean',
        'call_for_authorize_id' => 'string',
        'statement_descriptor' => 'string',
        'refunds' => 'json',
        'additional_info' => 'json',
        'campaign_id' => 'integer',
        'coupon_amount' => 'float',
        'installments' => 'integer',
        'token' => 'string',
        'description' => 'string',
        'notification_url' => 'string',
        'issuer_id' => 'string',
        'metadata' => 'json',
        'callback_url' => 'string',
        'coupon_code' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payment_id', 'id');
    }

    public function card(): HasOne
    {
        return $this->hasOne(Card::class, 'payment_id', 'id');
    }

    public function payer(): HasOne
    {
        return $this->hasOne(Payer::class, 'payment_id', 'id');
    }
}
