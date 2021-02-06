<?php

namespace App\Services;

use App\Exceptions\ValidatorException;
use App\Models\Payment\Card;
use App\Models\Payment\Payer;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Services\Payments\GatewayInterface;
use App\Services\Payments\MercadoPagoGateway;
use Illuminate\Auth\AuthManager;

class PaymentService
{
    private $authManager;

    private $payment;

    private $card;

    private $payer;

    public function __construct(AuthManager $auth, Payment $payment, Card $card, Payer $payer)
    {
        $this->authManager = $auth;

        $this->payment = $payment;

        $this->card = $card;

        $this->payer = $payer;
    }

    private $gatewayList = [
        'mercado-pago' => MercadoPagoGateway::class,
    ];

    private function getGateway(string $gateway, array $data): GatewayInterface
    {
        throw_unless(array_key_exists($gateway, $this->gatewayList), ValidatorException::class, ['gateway' => "Notfound  gateway $gateway"]);

        return new $this->gatewayList[$gateway]($data);
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

        $data['user_id'] = 1;
        $data['external_id'] = $data['id'] ?? null;

        // $data['user_id'] = $this->authManager->user()->id;

        return $data;
    }

    public function payment(string $gateway, array $data)
    {
        $gatewayMethod = $this->getGateway($gateway, $data);

        $driverPayment = $gatewayMethod->payment();

        throw_if($driverPayment->error !== null, ValidatorException::class, [$driverPayment->error->message ?? null]);

        $paymentData = $driverPayment->toArray();

        $payment = $this->payment->create(
            $this->mergeUser($paymentData)
        );

        $this->payer->create(
            $this->mergeUser($driverPayment->payer->toArray(), $payment)
        );

        $this->card->create(
            $this->mergeUser($driverPayment->card->toArray(), $payment)
        );
    }

    public function associate(User $user, $payument)
    {
    }
}
