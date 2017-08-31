<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class Currency implements TransformsValueInterface
{
    public function transform($value){
        return currency($value);
    }
}