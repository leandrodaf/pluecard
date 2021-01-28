<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public function fillAndSave(array $attributes): bool
    {
        $this->fill($attributes);

        if (count($this->getDirty()) > 0) {
            return $this->save();
        }

        return false;
    }
}
