<?php

namespace App\Services;

use App\Models\Payment\Item;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class ItemService
{
    private $item;

    /**
     * @param Item $item
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * @param array $data
     * @return Item
     */
    public function create(array $data): Item
    {
        return $this->item->create($data);
    }

    /** @return Paginator  */
    public function listItemsPaginate(): Paginator
    {
        return $this->item->simplePaginate(15);
    }

    /**
     * @param Item $item
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(Item $item, array $data): bool
    {
        return $item->fillAndSave($data);
    }

    /**
     * @param string $id
     * @return Item
     */
    public function show(string $id): Item
    {
        return $this->item->where('id', $id)->firstOrFail();
    }

    /**
     * @param Item $item
     * @return void
     * @throws Exception
     */
    public function destroy(Item $item): void
    {
        $item->delete();
    }
}
