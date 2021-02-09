<?php

namespace App\Http\Transformers;

use App\Models\Item;
use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'transactions',
    ];

    public function transform(Item $item)
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

    public function includeTransactions(Item $item)
    {
        return $this->collection($item->transactions, new TransactionTransformer);
    }
}
