<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class Datetime implements TransformsValueInterface
{
    public function transform($value){
        return timeZoned(Carbon::parse($value));
    }
}