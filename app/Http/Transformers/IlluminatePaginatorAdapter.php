<?php

namespace App\Http\Transformers;

use Illuminate\Contracts\Pagination\Paginator;
use League\Fractal\Pagination\PaginatorInterface;

class IlluminatePaginatorAdapter implements PaginatorInterface
{
    protected $paginator;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getCurrentPage()
    {
        return $this->paginator->currentPage();
    }

    public function getLastPage()
    {
        return $this->paginator->hasMorePages() ? $this->getCurrentPage() + 1 : $this->getCurrentPage();
    }

    public function getTotal()
    {
        return null;
    }

    public function getCount()
    {
        return $this->paginator->count();
    }

    public function getPerPage()
    {
        return $this->paginator->perPage();
    }

    public function getUrl($page)
    {
        return $this->paginator->url($page);
    }

    public function getPaginator()
    {
        return $this->paginator;
    }
}
