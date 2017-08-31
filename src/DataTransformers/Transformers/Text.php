<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;

class Text implements TransformsValueInterface {
    public function transform($value){
        return $value;
    }
}