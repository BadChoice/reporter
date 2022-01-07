<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\DataTransformers\Transformers\Currency;
use BadChoice\Reports\DataTransformers\Transformers\Decimal;
use BadChoice\Reports\DataTransformers\Transformers\SheetDecimal;
use Response;

class CsvExporter extends BaseExporter
{
    protected $output = '';

    public function __construct($fields, $collection)
    {
        parent::__construct($fields, $collection);
        app()->bind(Decimal::class, SheetDecimal::class);
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
            'Content-Type'        => 'application/csv; charset=UTF-8',
            'Content-Encoding'    => 'UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $title . '.csv"',  // Safari filename must be between commas
        ];
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function setOutput($output)
    {
        $this->output = $output;
        return $this;
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
        foreach ($exportFields = $this->getExportFields() as $index => $field) {
            $this->output .= $field->getTitle() . ($index === $exportFields->keys()->last() ? PHP_EOL : ';');
        }
    }

    private function writeRow($row)
    {
        foreach ($exportFields = $this->getExportFields() as $index => $field) {
            $this->output .= $field->getValue($row, false) . ($index === $exportFields->keys()->last() ? PHP_EOL : ';');
        }
    }

    protected function getType()
    {
        return "csv";
    }
}
