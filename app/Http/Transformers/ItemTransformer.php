<?php

namespace App\Http\Transformers;

use App\Models\Payment\PaymentItem;
use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'transactions',
    ];

    public function transform(PaymentItem $item)
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'picture_url' => $item->picture_url,
            'category_id' => $item->category_id,
            'unit_price' => $item->unit_price,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'deleted_at' => $item->updated_at,
        ];
    }

    public function includeTransactions(PaymentItem $item)
    {
        return $this->collection($item->transactions, new PaymentTransactionTransformer);
    }
}
