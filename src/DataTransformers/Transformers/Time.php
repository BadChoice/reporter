<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class Time implements TransformsValueInterface
{
    public function transform($value)
    {
        if (! $value) {
            return "--";
        }
        return timeZoned(Carbon::parse($value))->toTimeString();
    }
}
