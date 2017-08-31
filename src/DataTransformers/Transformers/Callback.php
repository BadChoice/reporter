<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class Callback implements TransformsRowInterface
{
    public function transformRow($object, $row){
        $callback = $row['field'];
        return $object->$callback();
    }
}