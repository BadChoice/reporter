<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Count implements TransformsValueInterface {
    public function transform($value) {
        return count($value);
    }
}