<?php

namespace App\Services\NPS;

use App\Services\NPS\Models\NPSResult;
use Carbon\Carbon;

interface MetricInterface
{
    public function result(string $entity, Carbon $startDate, Carbon $endDate, int | null $entityId): NPSResult;
}
