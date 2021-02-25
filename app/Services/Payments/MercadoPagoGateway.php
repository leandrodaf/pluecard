<?php

namespace App\Services\Payments;

use App\Exceptions\ValidatorException;
use App\Models\Item;
use Exception;
use MercadoPago\Payer as MercadoPagoPayer;
use MercadoPago\Payment as MercadoPagoPayment;
use MercadoPago\SDK as MercadoPagoSDK;
use Throwable;

class MercadoPagoGateway extends Gateway implements GatewayInterface
{
    protected $data = [];

    protected $validate = [];

    public function __construct(
        protected Item $item,
        array $data
    ) {
        $this->data = $data;

        MercadoPagoSDK::setAccessToken(config('payment.mercadopago.secret'));
    }

    /**
     * Get Item payment.
     *
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * Make payment.
     *
     * @return MercadoPagoPayment
     * @throws Exception
     * @throws Throwable
     */
    public function payment(): MercadoPagoPayment
    {
        $payment = new MercadoPagoPayment($this->data);

        $payment->transaction_amount = $this->getItem()->unit_price;

        $payment->payer = new MercadoPagoPayer($this->data['payer']);

        $payment->binary_mode = true;

        $payment->save();

        throw_unless(
            empty($payment->error),
            ValidatorException::class,
            [$payment->error->message],
            $payment->error->status
        );

        return  $payment;
    }
}
