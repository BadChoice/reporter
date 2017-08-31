<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class Month implements TransformsValueInterface {
    public function transform($value) {
        return getMonthName(is_int($value) ? $value : $value->timezone( auth()->user()->timezone )->month);
    }
}