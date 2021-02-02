<?php

namespace App\Services;

use App\Models\ModelStyle;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Pagination\Paginator;

class ModelStyleService
{
    private $modelStyle;

    /**
     * @param ModelStyle $modelStyle
     * @return void
     */
    public function __construct(ModelStyle $modelStyle)
    {
        $this->modelStyle = $modelStyle;
    }

    /**
     * @param array $data
     * @return ModelStyle
     */
    public function create(array $data): ModelStyle
    {
        return $this->modelStyle->create($data);
    }

    /** @return Paginator  */
    public function listModelStylePaginate(): Paginator
    {
        return $this->modelStyle->simplePaginate(15);
    }

    /**
     * @param ModelStyle $modelStyle
     * @param array $data
     * @return bool
     * @throws MassAssignmentException
     */
    public function update(ModelStyle $modelStyle, array $data): bool
    {
        return $modelStyle->fillAndSave($data);
    }

    /**
     * @param string $id
     * @return ModelStyle
     */
    public function show(string $id): ModelStyle
    {
        return $this->modelStyle->where('id', $id)->firstOrFail();
    }

    /**
     * @param ModelStyle $modelStyle
     * @return void
     * @throws Exception
     */
    public function destroy(ModelStyle $modelStyle): void
    {
        $modelStyle->delete();
    }
}
