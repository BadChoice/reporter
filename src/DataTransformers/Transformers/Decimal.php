<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class Decimal implements TransformsRowInterface
{
    public function transformRow($field, $row, $value, $commaSeparated)
    {
        if ($commaSeparated) {
            return number_format($value, 2, ',', '.');
        }
        return number_format($value, 2);
    }
}
