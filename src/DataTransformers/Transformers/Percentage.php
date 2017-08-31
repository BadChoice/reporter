<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class Percentage implements TransformsValueInterface
{
    public function transform($value){
        return number_format($value,2 ) . ' %';
    }
}