<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

class Percentage extends Decimal
{
    public function transform($value)
    {
        return parent::transform($value) . ' %';
    }
}
