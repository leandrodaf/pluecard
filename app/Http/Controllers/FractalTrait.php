<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;

trait FractalTrait
{
    /**
     * Create the response for an item.
     *
     * @param  mixed                                $item
     * @param  \League\Fractal\TransformerAbstract  $transformer
     * @param  int                                  $status
     * @param  array                                $headers
     * @return Response
     */
    protected function itemResponse($item, TransformerAbstract $transformer, int $status = 200, array $headers = []): Response
    {
        $resource = new Item($item, $transformer);

        return $this->buildResourceResponse($resource, $status, $headers);
    }

    /**
     * Create the response for a collection.
     *
     * @param  mixed                                $collection
     * @param  \League\Fractal\TransformerAbstract  $transformer
     * @param  int                                  $status
     * @param  array                                $headers
     * @return Response
     */
    protected function collectionResponse($collection, TransformerAbstract $transformer, $status = 200, array $headers = []): Response
    {
        $resource = new Collection($collection, $transformer);

        return $this->buildResourceResponse($resource, $status, $headers);
    }

    /**
     * Create the response for a resource.
     *
     * @param  \League\Fractal\Resource\ResourceAbstract  $resource
     * @param  int                                        $status
     * @param  array                                      $headers
     * @return Response
     */
    private function buildResourceResponse(ResourceAbstract $resource, $status = 200, array $headers = []): Response
    {
        $fractal = app('League\Fractal\Manager');

        return new Response($fractal->createData($resource)->toArray(), $status, $headers);
    }
}
