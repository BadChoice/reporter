<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class HourRange implements TransformsValueInterface {
    public function transform($value) {
        $hour = Carbon::parse($value)->timezone(auth()->user()->timezone)->hour;
        if ($hour >= 24) {
            $hour = $hour - 24;
        }
        return "{$hour}h - " . ($hour + 1) . "h";
    }
}