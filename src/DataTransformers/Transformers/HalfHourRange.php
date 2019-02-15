<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class HalfHourRange implements TransformsValueInterface
{
    public function transform($value)
    {
        $time       = Carbon::parse($value)->timezone(auth()->user()->timezone);
        $hour       = $this->fixHourIfBiggerThan23($time->hour);
        $endHour    = $this->fixHourIfMinutesBiggerThan30($time->minute, $hour);
        return $this->parseHour($hour) . ':30-' . $this->parseHour($endHour) . ':00';
    }

    private function fixHourIfBiggerThan23($hour)
    {
        return $hour >= 24 ? $hour - 24 : $hour;
    }

    private function fixHourIfMinutesBiggerThan30($minute, $hour)
    {
        if ($minute <= 30) {
            return $hour;
        }
        return $this->fixHourIfBiggerThan23($hour + 1);
    }

    private function parseHour($hour)
    {
        return str_pad($hour, 2, '0', STR_PAD_LEFT);
    }
}
