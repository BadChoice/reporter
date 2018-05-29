<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Decimal implements TransformsValueInterface
{
    public static $commaDecimal = false;

    public function transform($value)
    {
        if (static::$commaDecimal) {
            return number_format($value, 2, ',', '.');
        }
      	return number_format($value, 2);
      }
}
