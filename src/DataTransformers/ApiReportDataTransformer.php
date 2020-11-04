<?php

namespace BadChoice\Reports\DataTransformers;

class ApiReportDataTransformer extends ReportDataTransformer
{
    public static function transform($row, $field, $value, $transformation, $transformData = null)
    {
        $transformed = static::applyTransformation($row, $field, $value, $transformation, $transformData);
        return (is_string($transformed) && $transformed == "--") ? null : $transformed;
    }
}
