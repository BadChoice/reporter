<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class Hour implements TransformsValueInterface
{
    public function transform($value){
        return timeZoned($value)->hour . ":00";
    }
}