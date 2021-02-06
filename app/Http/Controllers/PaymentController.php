<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService, AuthManager $auth)
    {
        $this->paymentService = $paymentService;

        // Gate::authorize('adminOrSimpleUser', $auth->user());
    }

    public function payment(Request $request, string $gateway)
    {
        $data = $this->validate($request, [
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

        ]);

        $data = $request->all();

        $this->paymentService->payment($gateway, $data);
    }
}
