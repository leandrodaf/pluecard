<?php

namespace App\Providers;

use App\Http\Transformers\ApplicationSerializer;
use App\Http\Transformers\IlluminatePaginatorAdapter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class FractalServiceProviderque extends ServiceProvider
{
    public function boot()
    {
        $manager = $this->app->make('League\Fractal\Manager');

        $manager->parseIncludes(Request::input('include') ?? []);

        $manager->setSerializer(new ApplicationSerializer());

        response()->macro('item', fn (
            $item,
            TransformerAbstract $transformer,
            int $status = 200,
            array $headers = []
        ) => response()->json(
            $manager->createData(new Item($item, $transformer))->toArray(),
            $status,
            $headers
        ));

        response()->macro('collection', function (
            $collection,
            TransformerAbstract $transformer,
            int $status = 200,
            array $headers = []
        ) use ($manager) {
            $fractalCollection = new Collection($collection->flatten(), $transformer);

            if ($collection instanceof Paginator) {
                $fractalCollection->setPaginator(new IlluminatePaginatorAdapter($collection));
            }

            return response()->json(
                $manager->createData($fractalCollection)->toArray(),
                $status,
                $headers
            );
        });
    }
}
