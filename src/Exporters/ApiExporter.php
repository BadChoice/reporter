<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\DataTransformers\ApiReportDataTransformer;
use BadChoice\Reports\DataTransformers\ReportDataTransformer;
use BadChoice\Reports\DataTransformers\Transformers\Currency;
use BadChoice\Reports\DataTransformers\Transformers\Decimal;

class ApiExporter extends BaseExporter
{
    public function __construct($fields, $collection)
    {
        parent::__construct($fields, $collection);
        app()->bind(Currency::class, Decimal::class);
        app()->bind(ReportDataTransformer::class, ApiReportDataTransformer::class);
    }

    public function export()
    {
        parent::export();
        return $this->collection;
    }

    protected function init()
    {
    }

    protected function finalize()
    {
    }

    protected function generate()
    {
        $this->collection->getCollection()->transform(function($item, $key) {
            return $this->getExportFields()->mapWithKeys(function ($field) use (&$item) {
                return [$this->getFieldTitle($field) => $field->getValue($item)];
            });
        });
    }

    protected function getType()
    {
        return 'api';
    }

    protected function getFieldTitle($field)
    {
        return collect(explode('_', str_replace(['/', ' ', '.', ','], "_", $field->getTitle())))->map(function ($word) {
            return ctype_upper($word) ? strtolower($word) : snake_case($word);
        })->implode('_');
    }
}
