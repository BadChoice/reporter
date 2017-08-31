<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Month implements TransformsValueInterface {
    public function transform($value) {
        return getMonthName(is_int($value) ? $value : $value->timezone( auth()->user()->timezone )->month);
    }
}