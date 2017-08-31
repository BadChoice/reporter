<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class Date implements TransformsValueInterface
{
    public function transform($value){
        return substr(timeZoned($value),0,10);
    }
}