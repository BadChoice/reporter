<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class Text implements TransformsValueInterface {
    public function transform($value){
        return $value;
    }
}