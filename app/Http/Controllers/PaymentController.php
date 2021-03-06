<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use App\Services\PaymentService;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private ItemService $itemService,
        private AuthManager $authManager
    ) {
    }

    public function payment(Request $request, string $itemId, string $gateway): Response
    {
        Gate::authorize('adminOrSimpleUser', $this->authManager->user());

        $data = $this->validate($request, [
            'payer' => 'required|array',
            'payer.first_name' => 'required|string',
            'payer.last_name' => 'required|string',

            'payer.email' => 'required|email',

            'payer.phone' => 'array',
            'payer.phone.area_code' => 'string',
            'payer.phone.number' => 'string',

            'payer.identification' => 'required|array',
            'payer.identification.type' => 'required|string',
            'payer.identification.number' => 'required|string',

            'payer.address' => 'required|array',
            'payer.address.street_name' => 'required|string',
            'payer.address.street_number' => 'required|string',
            'payer.address.zip_code' => 'required|string',

            'issuer_id' => 'required|integer',
            'installments' => 'required|integer',
            'payment_method_id' => 'required|string',
            'description' => 'string|max:254',
            'token' => 'required|string',
        ]);

        $item = $this->itemService->show($itemId);

        try {
            DB::beginTransaction();

            $this->paymentService->payment($this->authManager->user(), $item, $gateway, $data);

            DB::commit();
        } catch (Exception $exec) {
            DB::rollBack();

            throw $exec;
        }

        return response(null, 201);
    }
}
