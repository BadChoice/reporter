<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Numeric implements TransformsValueInterface
{
    public function transform($value)
    {
        $value = (float) $value;
    	return $value ? : 0;
    }
}
