<?php

namespace App\Services;

use App\Models\Item;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class ItemService
{
    public function __construct(private Item $item)
    {
    }

    /**
     * Create new item.
     *
     * @param array $data
     * @return Item
     */
    public function create(array $data): Item
    {
        return $this->item->create($data);
    }

    /**
     * List All items with paginator.
     *
     * @return Paginator
     */
    public function listItemsPaginate(): Paginator
    {
        return $this->item->simplePaginate(15);
    }

    /**
     * Update item.
     *
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
     * Show single item.
     *
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
