<?php

namespace BadChoice\Reports\DataTransformers;

interface TransformsRowInterface{
    public function transformRow($field, $row, $value, $transformData);
}