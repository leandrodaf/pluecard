<?php

namespace App\Services\NPS;

use App\Exceptions\ValidatorException;
use App\Models\NPS;
use Carbon\Carbon;

abstract class AbstractMetrics
{
    const NOT_RELATION = 'NOT_RELATION';

    public function query(string $entity, Carbon $startDate, Carbon $endDate, int | null $entityId)
    {
        throw_if(
            $entity !== self::NOT_RELATION && $entityId === null,
            ValidatorException::class,
            ['entityId' => 'entityId cannot be null if the entity is different from NOT_RELATION']
        );

        $query = NPS::whereBetween('created_at', [$startDate, $endDate])
            ->where('relation', $entity);

        if ($entity !== self::NOT_RELATION) {
            $query->where('relation_id', $entityId);
        }

        return $query;
    }
}
