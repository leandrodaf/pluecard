<?php

namespace App\Http\Transformers;

use App\Models\Payment\PaymentItem;
use League\Fractal\TransformerAbstract;

class PaymentItemsTransformer extends TransformerAbstract
{
    public function transform(PaymentItem $paymentItem)
    {
        return [
            'title' => $paymentItem->title,
            'description' => $paymentItem->description,
            'picture_url' => $paymentItem->picture_url,
            'category_id' => $paymentItem->category_id,
            'unit_price' => $paymentItem->unit_price,
            'created_at' => $paymentItem->created_at,
            'updated_at' => $paymentItem->updated_at,
            'deleted_at' => $paymentItem->deleted_at,
        ];
    }
}
