<?php

namespace BadChoice\Reports\DataTransformers;

interface TransformsRowInterface{
    public function transformRow($key, $value, $transformData);
}