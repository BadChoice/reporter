<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class Date implements TransformsValueInterface
{
    public function transform($value){
        return substr(timeZoned(Carbon::parse($value)),0,10);
    }
}