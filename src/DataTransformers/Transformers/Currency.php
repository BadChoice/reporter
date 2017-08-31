<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Currency implements TransformsValueInterface
{
    public function transform($value){
        return currency($value);
    }
}