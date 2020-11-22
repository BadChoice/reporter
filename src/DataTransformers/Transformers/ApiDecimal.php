<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class ApiDecimal implements TransformsValueInterface
{
    public function transform($value)
    {
        return round($value, 2);
    }
}
