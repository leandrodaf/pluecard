<?php

namespace App\Models;

use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use InvalidArgumentException;

class Model extends EloquentModel
{
    /**
     * Updates an entity only if the values are different.
     *
     * @param array $attributes
     * @return bool
     * @throws MassAssignmentException
     * @throws InvalidCastException
     * @throws InvalidArgumentException
     */
    public function fillAndSave(array $attributes): bool
    {
        $this->fill($attributes);

        if (count($this->getDirty()) > 0) {
            return $this->save();
        }

        return false;
    }
}
