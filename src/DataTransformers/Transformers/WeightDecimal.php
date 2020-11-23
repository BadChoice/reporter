<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class WeightDecimal implements TransformsValueInterface
{
    public static $commaDecimal = false;

    public function transform($value)
    {
        if (static::$commaDecimal) {
            return number_format($value, 3, ',', '.');
        }
        return number_format($value, 3, '.', ',');
    }
}
