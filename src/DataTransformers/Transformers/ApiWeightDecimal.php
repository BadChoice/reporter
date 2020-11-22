<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class ApiWeightDecimal implements TransformsValueInterface
{
    public function transform($value)
    {
        return round($value, 3);
    }
}
