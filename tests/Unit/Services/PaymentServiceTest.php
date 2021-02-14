<?php

use App\Models\Item;
use App\Models\Payment\Card as PaymentCard;
use App\Models\Payment\Payer;
use App\Models\Payment\Payment;
use App\Models\Payment\Transaction;
use App\Models\User;
use App\Services\CardService;
use App\Services\PayerService;
use App\Services\Payments\GatewayInterface;
use App\Services\Payments\GatewayMethod;
use App\Services\PaymentService;
use App\Services\TransactionService;

class PaymentServiceTest extends TestCase
{
    public function testPaymentMethod()
    {
        $user = User::factory()->make(['id' => 123]);

        $fakeitem = Item::factory()->make();

        $fakePayment = new Payment(['installments' => 2]);

        $fakePayer = new Payer();

        $fakeCard = new PaymentCard();

        $fakeTransaction = new Transaction();

        $gateway = 'mercado-pago';

        $data = [];

        $this->mockDependence(GatewayMethod::class, function (GatewayMethod $gatewayMethod) use ($gateway, $fakeitem, $data) {
            $gatewayInterface = Mockery::mock(GatewayInterface::class);

            $MELIPayment = Mockery::mock(stdClass::class);
            $MELIPayment->shouldReceive('toArray')->once()->andReturn(['id' => 123456]);

            $MELIPayment->payer = Mockery::mock(stdClass::class);
            $MELIPayment->payer->shouldReceive('toArray')->once()->andReturn(['id' => 45678]);

            $MELIPayment->card = Mockery::mock(stdClass::class);
            $MELIPayment->card->shouldReceive('toArray')->once()->andReturn(['id' => 45678]);

            $gatewayInterface->shouldReceive('payment')->andReturn($MELIPayment);

            $gatewayMethod->shouldReceive('get')->with($gateway, $fakeitem, $data)->once()->andReturn($gatewayInterface);

            return $gatewayMethod;
        });

        $this->mockDependence(Payment::class, function (Payment $payment) use ($fakePayment) {
            $payment
                ->shouldReceive('create')->with(['id' => 123456, 'user_id' => 123, 'external_id' => 123456])
                ->once()
                ->andReturn($fakePayment);

            return $payment;
        });

        $this->mockDependence(PayerService::class, function (PayerService $payerService) use ($fakePayment, $fakePayer) {
            $payerService
                ->shouldReceive('createByPayment')->with($fakePayment, ['id' => 45678, 'external_id' => 45678])
                ->once()
                ->andReturn($fakePayer);

            return $payerService;
        });

        $this->mockDependence(CardService::class, function (CardService $cardService) use ($fakePayment, $fakeCard) {
            $cardService
                ->shouldReceive('createByPayment')->with($fakePayment, ['id' => 45678, 'external_id' => 45678])
                ->once()
                ->andReturn($fakeCard);

            return $cardService;
        });

        $this->mockDependence(TransactionService::class, function (TransactionService $transactionService) use ($fakeTransaction) {
            $transactionService
                ->shouldReceive('createByPayment')
                ->once()
                ->andReturn($fakeTransaction);

            return $transactionService;
        });

        $this->app->make(PaymentService::class)->payment($user, $fakeitem, $gateway, $data);
    }
}
