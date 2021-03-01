<?php

return [
    'metrics' => [
        'DETRACTORS' => App\Services\NPS\Detractors::class,
        'LIABILITIES' => App\Services\NPS\Liabilities::class,
        'PROMOTERS' => App\Services\NPS\Promoterss::class,
    ],

    'relationships' => [
        'NOT_RELATION' => null,
    ],
];
