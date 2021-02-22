<?php

namespace App\Services;

use App\Models\Card\StyleCard;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class StyleService
{
    /**
     * @param StyleCard $style
     * @return void
     */
    public function __construct(private StyleCard $style)
    {
    }

    /**
     * @param array $data
     * @return StyleCard
     */
    public function create(array $data): StyleCard
    {
        return $this->style->create($data);
    }

    /** @return Paginator  */
    public function listStylePaginate(): Paginator
    {
        return $this->style->simplePaginate(15);
    }

    /**
     * @param StyleCard $style
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(StyleCard $style, array $data): bool
    {
        return $style->fillAndSave($data);
    }

    /**
     * @param string $id
     * @return StyleCard
     */
    public function show(string $id): StyleCard
    {
        return $this->style->where('id', $id)->firstOrFail();
    }

    /**
     * @param StyleCard $style
     * @return void
     * @throws Exception
     */
    public function destroy(StyleCard $style): void
    {
        $style->delete();
    }
}
