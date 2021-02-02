<?php

namespace App\Services;

use App\Models\ModelStyle;

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
}
