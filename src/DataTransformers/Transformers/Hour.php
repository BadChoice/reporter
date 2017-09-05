<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Hour implements TransformsValueInterface
{
    public function transform($value) {
        return timeZoned($value)->hour . ":00";
    }
}