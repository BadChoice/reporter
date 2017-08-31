<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;
use Carbon\Carbon;

class Datetime implements TransformsValueInterface
{
    public function transform($value){
        $value = is_string($value) ? Carbon::parse($value) : $value;
        return timeZoned($value);
    }
}