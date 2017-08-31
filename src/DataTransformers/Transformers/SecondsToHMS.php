<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class SecondsToHMS implements TransformsValueInterface
{
    public function transform($seconds) {
        return secondsToHMS($seconds);
    }
}