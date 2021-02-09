<?php

namespace App\Services;

use App\Exceptions\ValidatorException;
use App\Models\Payment\Card;
use App\Models\Payment\Payer;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentItem;
use App\Models\Payment\Transactions;
use App\Services\Payments\GatewayInterface;
use App\Services\Payments\MercadoPagoGateway;
use Illuminate\Auth\AuthManager;
use Throwable;

class PaymentService
{
    private $authManager;

    private $payment;

    private $card;

    private $payer;

    private $transactions;

    private $gatewayList = [
        'mercado-pago' => MercadoPagoGateway::class,
    ];

    /**
     * @param AuthManager $auth
     * @param Payment $payment
     * @param Card $card
     * @param Payer $payer
     * @return void
     */
    public function __construct(
        AuthManager $auth,
        Payment $payment,
        Card $card,
        Payer $payer,
        Transactions $transactions
    ) {
        $this->authManager = $auth;

        $this->payment = $payment;

        $this->card = $card;

        $this->payer = $payer;

        $this->transactions = $transactions;
    }

    /**
     * @param string $gateway
     * @param PaymentItem $paymentItem
     * @param array $data
     * @return GatewayInterface
     * @throws Throwable
     */
    private function getGateway(string $gateway, PaymentItem $paymentItem, array $data): GatewayInterface
    {
        throw_unless(array_key_exists($gateway, $this->gatewayList), ValidatorException::class, ['gateway' => "Notfound  gateway $gateway"]);

        return new $this->gatewayList[$gateway]($paymentItem, $data);
    }

    /**
     * @param array $data
     * @param Payment|null $payment
     * @return array
     */
    private function mergeUser(array $data, Payment $payment = null): array
    {
        if ($payment !== null) {
            $data['payment_id'] = $payment->id;
        }

        $data['user_id'] = $this->authManager->user()->id;
        $data['external_id'] = $data['id'] ?? null;

        return $data;
    }

    /**
     * @param PaymentItem $paymentItem
     * @param string $gateway
     * @param array $data
     * @return void
     * @throws Throwable
     */
    public function payment(PaymentItem $paymentItem, string $gateway, array $data)
    {
        $gatewayMethod = $this->getGateway($gateway, $paymentItem, $data);

        $driverPayment = $gatewayMethod->payment();

        $paymentData = $driverPayment->toArray();

        $payment = $this->payment->create(
            $this->mergeUser($paymentData)
        );

        $payer = $this->payer->create(
            $this->mergeUser($driverPayment->payer->toArray(), $payment)
        );

        $card = $this->card->create(
            $this->mergeUser($driverPayment->card->toArray(), $payment)
        );

        $this->transactions->create([
            'currency_id' => 'BRL',
            'amount' => $paymentItem->unit_price,
            'quantity' => 1,
            'installments' => $payment->installments,
            'user_id' => $this->authManager->user()->id,
            'payments_item_id' => $paymentItem->id,
            'payment_id' => $payment->id,
            'payments_payer_id' => $payer->id,
            'payments_card_id' => $card->id,
        ]);
    }
}
