<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use App\Models\User;
use App\Services\Payments\GatewayMethod;

class PaymentService
{
    private $payment;

    private $gatewayMethod;

    private $payerService;

    private $cardService;

    private $transactionService;

    public function __construct(
        Payment $payment,
        GatewayMethod $gatewayMethod,
        PayerService $payerService,
        CardService $cardService,
        TransactionService $transactionService
    ) {
        $this->payment = $payment;

        $this->gatewayMethod = $gatewayMethod;

        $this->payerService = $payerService;

        $this->cardService = $cardService;

        $this->transactionService = $transactionService;
    }

    /**
     * Create new payment.
     *
     * @param User $user
     * @param Item $item
     * @param string $gateway
     * @param array $data
     * @return Transaction
     */
    public function payment(User $user, Item $item, string $gateway, array $data): void
    {
        $gatewayMethod = $this->gatewayMethod->get($gateway, $item, $data);

        $driverPayment = $gatewayMethod->payment();

        $paymentData = $driverPayment->toArray();

        $paymentData['user_id'] = $user->id;
        $paymentData['external_id'] = $paymentData['id'] ?? null;

        $payment = $this->payment->create($paymentData);

        $payerData = $driverPayment->payer->toArray();
        $payerData['external_id'] = $payerData['id'];
        $payer = $this->payerService->createByPayment($payment, $payerData);

        $cardData = $driverPayment->card->toArray();
        $cardData['external_id'] = $cardData['id'];
        $card = $this->cardService->createByPayment($payment, $cardData);

        $this->transactionService->createByPayment($payment, $payer, $card, $item, [
            'currency_id' => 'BRL',
            'amount' => $item->unit_price,
            'quantity' => 1,
            'installments' => $payment->installments,
        ]);
    }
}
