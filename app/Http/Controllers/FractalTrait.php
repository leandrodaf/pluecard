<?php

namespace App\Http\Controllers;

use App\Http\Transformers\IlluminatePaginatorAdapter;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;

trait FractalTrait
{
    protected function itemResponse($item, TransformerAbstract $transformer, int $status = 200, array $headers = []): Response
    {
        $resource = new Item($item, $transformer);

        return $this->buildResourceResponse($resource, $status, $headers);
    }

    protected function collectionResponse($collection, TransformerAbstract $transformer, $status = 200, array $headers = []): Response
    {
        $resource = new Collection($collection, $transformer);

        return $this->buildResourceResponse($resource, $status, $headers);
    }

    protected function paginateResponse(Paginator $paginator, TransformerAbstract $transformer, $status = 200, array $headers = []): Response
    {
        $resource = new Collection($paginator->getCollection(), $transformer);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->buildResourceResponse($resource, $status, $headers);
    }

    private function buildResourceResponse(ResourceAbstract $resource, $status = 200, array $headers = []): Response
    {
        $fractal = app('League\Fractal\Manager');

        return new Response($fractal->createData($resource)->toArray(), $status, $headers);
    }
}
