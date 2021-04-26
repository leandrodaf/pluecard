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

    public function getCurrentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function getLastPage(): int
    {
        return $this->paginator->hasMorePages() ? $this->getCurrentPage() + 1 : $this->getCurrentPage();
    }

    public function getTotal(): ?int
    {
        return null;
    }

    public function getCount(): int
    {
        return $this->paginator->count();
    }

    public function getPerPage(): int
    {
        return $this->paginator->perPage();
    }

    public function getUrl($page): string
    {
        return $this->paginator->url($page);
    }

    public function getPaginator()
    {
        return $this->paginator;
    }
}
