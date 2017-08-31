<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsRowInterface;

class Callback implements TransformsRowInterface
{
    public function transformRow($object, $row){
        $callback = $row['field'];
        return $object->$callback();
    }
}