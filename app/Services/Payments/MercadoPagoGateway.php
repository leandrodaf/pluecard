<?php

namespace App\Services\Payments;

use App\Exceptions\ValidatorException;
use App\Models\Payment\PaymentItem;
use MercadoPago\Payer as MercadoPagoPayer;
use MercadoPago\Payment as MercadoPagoPayment;
use MercadoPago\SDK as MercadoPagoSDK;

class MercadoPagoGateway extends Gateway implements GatewayInterface
{
    protected $paymentItem;

    protected $data;

    public function __construct(PaymentItem $paymentItem, array $data)
    {
        $this->paymentItem = $paymentItem;

        $this->data = $data;

        MercadoPagoSDK::setAccessToken(config('payment.mercadopago.secret'));
    }

    public function getItem(): PaymentItem
    {
        return $this->paymentItem;
    }

    public function payment(): MercadoPagoPayment
    {
        $payment = new MercadoPagoPayment($this->data);

        $payment->transaction_amount = $this->getItem()->unit_price;

        $payment->payer = new MercadoPagoPayer($this->data['payer']);

        $payment->binary_mode = true;

        $payment->save();

        throw_unless(empty($payment->error), ValidatorException::class, [$payment->error->message], $payment->error->status);

        return  $payment;
    }
}
