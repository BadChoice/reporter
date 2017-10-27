<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class CalculatedAttribute implements TransformsRowInterface
{
    public function transformRow($field, $row, $value, $transformData)
    {
        return $row->{$field};
    }
}
