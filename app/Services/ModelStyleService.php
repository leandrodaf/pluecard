<?php

namespace App\Services;

use App\Models\ModelStyle;

class ModelStyleService
{
    private $modelStyle;

    public function __construct(ModelStyle $modelStyle)
    {
        $this->modelStyle = $modelStyle;
    }

    public function create(array $data)
    {
        dd(
            $data
        );
        $this->modelStyle->create($data);
    }
}
