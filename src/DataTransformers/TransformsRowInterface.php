<?php

namespace BadChoice\Reports\DataTransformer;

interface TransformsRowInterface{
    public function transformRow($object, $row);
}