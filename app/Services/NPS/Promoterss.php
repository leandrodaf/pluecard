<?php

namespace App\Services\NPS;

use App\Services\NPS\Models\NPSResult;
use Carbon\Carbon;

class Promoterss extends AbstractMetrics implements MetricInterface
{
    public function result(string $entity, Carbon $startDate, Carbon $endDate, int | null $entityId): NPSResult
    {
        $quantity = $this->query($entity, $startDate, $endDate, $entityId)->whereIn('rating', [9, 10])->count();
        $total = $this->query($entity, $startDate, $endDate, $entityId)->count();
        $rating = ceil(($quantity * 100) / $total);

        return new NPSResult(
            'PROMOTERS',
            $quantity,
            $total,
            $rating,
            $entity,
            $entityId
        );
    }
}
