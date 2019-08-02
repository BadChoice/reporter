<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Numeric implements TransformsValueInterface
{
    public function transform($value)
    {
    	return $value ? : 0;
    }
}
