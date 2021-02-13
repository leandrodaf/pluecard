<?php

namespace App\Services;

use App\Models\Card\Style;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class StyleService
{
    private $style;

    /**
     * @param Style $style
     * @return void
     */
    public function __construct(Style $style)
    {
        $this->style = $style;
    }

    /**
     * @param array $data
     * @return Style
     */
    public function create(array $data): Style
    {
        return $this->style->create($data);
    }

    /** @return Paginator  */
    public function listStylePaginate(): Paginator
    {
        return $this->style->simplePaginate(15);
    }

    /**
     * @param Style $style
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(Style $style, array $data): bool
    {
        return $style->fillAndSave($data);
    }

    /**
     * @param string $id
     * @return Style
     */
    public function show(string $id): Style
    {
        return $this->style->where('id', $id)->firstOrFail();
    }

    /**
     * @param Style $style
     * @return void
     * @throws Exception
     */
    public function destroy(Style $style): void
    {
        $style->delete();
    }
}
