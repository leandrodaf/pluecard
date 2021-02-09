<?php

namespace App\Services;

use App\Models\Payment\PaymentItem;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class PaymentItemService
{
    private $paymentItem;

    /**
     * @param PaymentItem $paymentItem
     * @return void
     */
    public function __construct(PaymentItem $paymentItem)
    {
        $this->paymentItem = $paymentItem;
    }

    /**
     * @param array $data
     * @return PaymentItem
     */
    public function create(array $data): PaymentItem
    {
        return $this->paymentItem->create($data);
    }

    /** @return Paginator  */
    public function listPaymentItemsPaginate(): Paginator
    {
        return $this->paymentItem->simplePaginate(15);
    }

    /**
     * @param PaymentItem $paymentItem
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(PaymentItem $paymentItem, array $data): bool
    {
        return $paymentItem->fillAndSave($data);
    }

    /**
     * @param string $id
     * @return PaymentItem
     */
    public function show(string $id): PaymentItem
    {
        return $this->paymentItem->where('id', $id)->firstOrFail();
    }

    /**
     * @param PaymentItem $paymentItem
     * @return void
     * @throws Exception
     */
    public function destroy(PaymentItem $paymentItem): void
    {
        $paymentItem->delete();
    }
}
