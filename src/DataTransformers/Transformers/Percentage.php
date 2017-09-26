<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Percentage implements TransformsValueInterface {
    public function transform($value) {
        return number_format($value,2 ) . ' %';
    }
}