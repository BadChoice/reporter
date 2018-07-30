<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

class SheetDecimal extends Decimal
{
    public function transform($value)
    {
        if (static::$commaDecimal) {
            return number_format($value, 2, ',', '');
        }
        return number_format($value, 2, '.', '');
    }
}
