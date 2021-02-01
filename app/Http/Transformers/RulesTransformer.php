<?php

namespace App\Http\Transformers;

use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

class RulesTransformer extends TransformerAbstract
{
    public function transform(Collection $rule)
    {
        return $rule->toArray();
    }
}
