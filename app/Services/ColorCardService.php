<?php

namespace App\Services;

use App\Models\Card\ColorCard;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class ColorCardService
{
    private $colorCard;

    /**
     * @param ColorCard $colorCard
     * @return void
     */
    public function __construct(ColorCard $colorCard)
    {
        $this->colorCard = $colorCard;
    }

    /**
     * Create new Color.
     *
     * @param array $data
     * @return ColorCard
     */
    public function create(array $data): ColorCard
    {
        return $this->colorCard->create($data);
    }

    /**
     * List All Colors with paginate.
     *
     * @return Paginator
     */
    public function listColorCardPaginate(): Paginator
    {
        return $this->colorCard->simplePaginate(15);
    }

    /**
     * Update color.
     *
     * @param ColorCard $colorCard
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(ColorCard $colorCard, array $data): bool
    {
        return $colorCard->fillAndSave($data);
    }

    /**
     * Show single color.
     *
     * @param string $id
     * @return ColorCard
     */
    public function show(string $id): ColorCard
    {
        return $this->colorCard->where('id', $id)->firstOrFail();
    }

    /**
     * Delete Color.
     *
     * @param ColorCard $colorCard
     * @return void
     * @throws Exception
     */
    public function destroy(ColorCard $colorCard): void
    {
        $colorCard->delete();
    }
}
