<?php

namespace App\Services\NPS\Models;

class NPSResult
{
    public function __construct(
        public string $metric,
        public int $quantity,
        public int $total,
        public float $rating,
        public string $entity,
        public int | null $entityId,
    ) {
    }
}
