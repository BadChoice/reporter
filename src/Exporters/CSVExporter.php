<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\DataTransformers\Transformers\Currency;
use BadChoice\Reports\DataTransformers\Transformers\Decimal;
use Response;

class CsvExporter extends BaseExporter
{
    protected $output = '';

    public function __construct($fields, $collection) {
        parent::__construct($fields, $collection);
        app()->bind(Currency::class, Decimal::class);
    }

    public function download($title)
    {
        return $this->makeResponse($title);
    }

    public function save($filename)
    {
        file_put_contents($filename, $this->output);
    }

    public function print()
    {
        return $this->output;
    }

    private function getHeaders($title)
    {
        return [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$title.'.csv"',
        ];
    }

    private function makeResponse($title)
    {
        return Response::make(rtrim($this->output, "\n"), 200, $this->getHeaders($title));
    }

    public function init()
    {
    }

    public function finalize()
    {
    }

    public function generate()
    {
        $this->writeHeader();
        $this->forEachRecord(function ($row) {
            $this->writeRow($row);
        });
    }

    private function writeHeader()
    {
        foreach ($this->getExportFields() as $field) {
            $this->output .= $field->getTitle() . ';';
        }
        $this->output .= PHP_EOL;
    }

    private function writeRow($row)
    {
        foreach ($this->getExportFields() as $field) {
            $this->output .= $field->getValue($row) . ';';
        }
        $this->output .= PHP_EOL;
    }

    protected function getType()
    {
        return "csv";
    }
}
