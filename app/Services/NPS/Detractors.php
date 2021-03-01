<?php

namespace App\Services\NPS;

use App\Services\NPS\Models\NPSResult;
use Carbon\Carbon;

class Detractors extends AbstractMetrics implements MetricInterface
{
    const NOT_RELATION = 'NOT_RELATION';

    public function result(string $entity, Carbon $startDate, Carbon $endDate, int | null $entityId): NPSResult
    {
        $quantity = $this->query($entity, $startDate, $endDate, $entityId)->whereIn('rating', [0, 1, 2, 3, 4, 5, 6])->count();
        $total = $this->query($entity, $startDate, $endDate, $entityId)->count();
        $rating = ($quantity * 100) / $total;

        return new NPSResult(
            'DETRACTORS',
            $quantity,
            $total,
            $rating,
            $entity,
            $entityId
        );
    }
}
