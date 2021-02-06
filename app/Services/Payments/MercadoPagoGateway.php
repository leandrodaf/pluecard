<?php

namespace App\Services\Payments;

use MercadoPago\Payer as MercadoPagoPayer;
use MercadoPago\Payment as MercadoPagoPayment;
use MercadoPago\SDK as MercadoPagoSDK;

class MercadoPagoGateway extends Gateway implements GatewayInterface
{
    protected $data;

    protected $validate = [
        'payer' => 'required|array',
        'payer.name' => 'required|string',
        'payer.surname' => 'required|string',

        'payer.email' => 'required|email',

        'payer.phone' => 'required|array',
        'payer.phone.area_code' => 'required|string',
        'payer.phone.number' => 'required|string',

        'payer.identification' => 'required|array',
        'payer.identification.type' => 'required|string',
        'payer.identification.number' => 'required|string',

        'payer.address' => 'required|array',
        'payer.address.street_name' => 'required|string',
        'payer.address.street_number' => 'required|string',
        'payer.address.zip_code' => 'required|string',

        'issuer' => 'required|integer',
        'installments' => 'required|integer',
        'transaction_amount' => 'required|numeric',
        'payment_method_id' => 'required|string',
        'description' => 'string|max:254',
        'token' => 'required|string',
    ];

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->validate();

        MercadoPagoSDK::setAccessToken(config('payment.mercadopago.secret'));
    }

    public function payment(): MercadoPagoPayment
    {
        $payment = new MercadoPagoPayment();

        $payment->transaction_amount = $this->data['transactionAmount'];
        $payment->token = $this->data['token'];
        $payment->description = $this->data['description'];
        $payment->installments = $this->data['installments'];
        $payment->payment_method_id = $this->data['paymentMethodId'];
        $payment->issuer_id = $this->data['issuer'];

        $payer = new MercadoPagoPayer();
        $payer->email = $this->data['payer']['email'];
        $payer->identification = $this->data['payer']['identification'];

        $payment->payer = $payer;

        $payment->save();

        return  $payment;
    }
}
