<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class DateDay implements TransformsValueInterface
{
    public function transform($value){
        $value = is_string($value) ? Carbon::parse($value) : $value;
        Carbon::parse($value)->timezone( auth()->user()->timezone )->day;
    }
}