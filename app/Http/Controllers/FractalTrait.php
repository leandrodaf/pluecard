<?php

namespace App\Http\Controllers;

use App\Http\Transformers\IlluminatePaginatorAdapter;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Request;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;

trait FractalTrait
{
    /**
     * Default response single item api.
     *
     * @param mixed $item
     * @param TransformerAbstract $transformer
     * @param int $status
     * @param array $headers
     * @return Response
     * @throws BindingResolutionException
     */
    protected function itemResponse($item, TransformerAbstract $transformer, int $status = 200, array $headers = []): Response
    {
        $resource = new Item($item, $transformer);

        return $this->buildResourceResponse($resource, $status, $headers);
    }

    /**
     * Default response collection items api.
     *
     * @param mixed $collection
     * @param TransformerAbstract $transformer
     * @param int $status
     * @param array $headers
     * @return Response
     * @throws BindingResolutionException
     */
    protected function collectionResponse($collection, TransformerAbstract $transformer, $status = 200, array $headers = []): Response
    {
        $resource = new Collection($collection, $transformer);

        return $this->buildResourceResponse($resource, $status, $headers);
    }

    /**
     * Default response for collection paginated api.
     *
     * @param Paginator $paginator
     * @param TransformerAbstract $transformer
     * @param int $status
     * @param array $headers
     * @return Response
     * @throws BindingResolutionException
     */
    protected function paginateResponse(Paginator $paginator, TransformerAbstract $transformer, $status = 200, array $headers = []): Response
    {
        $resource = new Collection($paginator->getCollection(), $transformer);

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->buildResourceResponse($resource, $status, $headers);
    }

    /**
     * Make fractal instance for serializer api.
     *
     * @param ResourceAbstract $resource
     * @param int $status
     * @param array $headers
     * @return Response
     * @throws BindingResolutionException
     */
    private function buildResourceResponse(ResourceAbstract $resource, $status = 200, array $headers = []): Response
    {
        $fractal = app('League\Fractal\Manager');

        $fractal->parseIncludes(Request::input('include') ?? []);

        return new Response($fractal->createData($resource)->toArray(), $status, $headers);
    }
}
