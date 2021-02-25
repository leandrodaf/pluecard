<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class FractalServiceProviderque extends ServiceProvider
{
    public function boot()
    {
        $fractal = $this->app->make('League\Fractal\Manager');

        response()->macro('item', fn (
            $item,
            TransformerAbstract $transformer,
            int $status = 200,
            array $headers = []
        ) => response()->json(
            $fractal->createData(new Item($item, $transformer))->toArray(),
            $status,
            $headers
        ));

        response()->macro('collection', fn (
            $collection,
            TransformerAbstract $transformer,
            int $status = 200,
            array $headers = []
        ) => response()->json(
            $fractal->createData(new Collection($collection, $transformer))->toArray(),
            $status,
            $headers
        ));
    }
}
