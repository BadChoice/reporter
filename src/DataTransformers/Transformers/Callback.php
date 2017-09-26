<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class Callback implements TransformsRowInterface {
    public function transformRow($field, $row, $value, $transformData) {
        return $transformData($value);
        /*$callback = $transformData; //TODO
        return $row->$callback();*/
    }
}