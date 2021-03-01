<?php

namespace App\Services;

use App\Models\NPS;
use App\Models\User;
use App\Services\NPS\MetricInterface;
use App\Services\NPS\Models\NPSResult;
use Carbon\Carbon;

class NPSService
{
    const NOT_RELATION = 'NOT_RELATION';

    public function __construct(
        private NPS $NPS
    ) {
    }

    public function rating(User $user, string $relation, int $rating, int $relationId = null): NPS
    {
        if ($relation === self::NOT_RELATION) {
            $relationId = null;
        }

        return $this->NPS->create([
            'relation' => $relation,
            'relation_id' => $relationId,
            'rating' => $rating,
            'user_id' => $user->id,
        ]);
    }

    private function getMetric(string $metricId): MetricInterface
    {
        return app(config('NPS.metrics')[$metricId]);
    }

    public function metric(
        string $metricId,
        Carbon $startDate,
        Carbon $endDate,
        string $entity,
        int | null $entityId
    ): NPSResult {
        return $this->getMetric($metricId)->result($entity, $startDate, $endDate, $entityId);
    }
}
