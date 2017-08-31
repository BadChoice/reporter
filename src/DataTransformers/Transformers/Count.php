<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class Count implements TransformsValueInterface
{
    public function transform($value){
        return count($value);
    }
}