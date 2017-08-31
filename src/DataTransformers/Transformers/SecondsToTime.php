<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class SecondsToTime implements TransformsValueInterface
{
    public function transform($seconds) {
        return secondsToHMS($seconds);
    }
}