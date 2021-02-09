<?php

namespace App\Http\Transformers;

use App\Models\Payment\Payment;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'card', 'payer', 'transactions', 'user',
    ];

    public function transform(Payment $payment)
    {
        return [
            'id' => $payment->id,
            'external_id' => $payment->external_id,
            'operation_type' => $payment->operation_type,
            'order' => $payment->order,
            'binary_mode' => $payment->binary_mode,
            'external_reference' => $payment->external_reference,
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'date_created' => $payment->date_created,
            'last_modified' => $payment->last_modified,
            'live_mode' => $payment->live_mode,
            'date_last_updated' => $payment->date_last_updated,
            'date_of_expiration' => $payment->date_of_expiration,
            'date_approved' => $payment->date_approved,
            'money_release_date' => $payment->money_release_date,
            'currency_id' => $payment->currency_id,
            'transaction_amount' => $payment->transaction_amount,
            'transaction_amount_refunded' => $payment->transaction_amount_refunded,
            'collector_id' => $payment->collector_id,
            'payment_method_id' => $payment->payment_method_id,
            'payment_type_id' => $payment->payment_type_id,
            'transaction_details' => $payment->transaction_details,
            'fee_details' => $payment->fee_details,
            'differential_pricing_id' => $payment->differential_pricing_id,
            'application_fee' => $payment->application_fee,
            'capture' => $payment->capture,
            'captured' => $payment->captured,
            'call_for_authorize_id' => $payment->call_for_authorize_id,
            'statement_descriptor' => $payment->statement_descriptor,
            'refunds' => $payment->refunds,
            'additional_info' => $payment->additional_info,
            'campaign_id' => $payment->campaign_id,
            'coupon_amount' => $payment->coupon_amount,
            'installments' => $payment->installments,
            'token' => $payment->token,
            'description' => $payment->description,
            'notification_url' => $payment->notification_url,
            'issuer_id' => $payment->issuer_id,
            'metadata' => $payment->metadata,
            'callback_url' => $payment->callback_url,
            'coupon_code' => $payment->coupon_code,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at,
        ];
    }

    public function includeUser(Payment $payment)
    {
        return $this->item($payment->user, new UserTransformer);
    }

    public function includeCard(Payment $payment)
    {
        return $this->item($payment->card, new CardTransformer);
    }

    public function includePayer(Payment $payment)
    {
        return $this->item($payment->payer, new PayerTransformer);
    }

    public function includeTransactions(Payment $payment)
    {
        return $this->collection($payment->transactions, new TransactionTransformer);
    }
}
