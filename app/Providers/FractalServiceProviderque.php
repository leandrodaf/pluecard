<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FractalServiceProviderque extends ServiceProvider
{
    public function boot()
    {
        $fractal = $this->app->make('League\Fractal\Manager');

        response()->macro('item', function ($item, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal) {
            $resource = new \League\Fractal\Resource\Item($item, $transformer);

            return response()->json(
                $fractal->createData($resource)->toArray(),
                $status,
                $headers
            );
        });

        response()->macro('collection', function ($collection, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal) {
            $resource = new \League\Fractal\Resource\Collection($collection, $transformer);

            return response()->json(
                $fractal->createData($resource)->toArray(),
                $status,
                $headers
            );
        });
    }
}
