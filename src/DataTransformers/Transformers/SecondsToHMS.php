<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class SecondsToHMS implements TransformsValueInterface
{
    public function transform($seconds) {
        return secondsToHMS($seconds);
    }
}