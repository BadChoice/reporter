<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class CallObjectMethod implements TransformsRowInterface
{
    public function transformRow($field, $row, $value, $transformData)
    {
        $callback = $transformData;
        return $row->$callback();
    }
}
