<?php

namespace App\Services\NPS\Models;

class NPSResult
{
    public function __construct(
        public string $metric,
        public int $total,
        public string $entity,
        public int | null $entityId,
    ) {
    }
}
