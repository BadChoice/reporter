<?php

namespace BadChoice\Reports;

use BadChoice\Reports\Exporters\FakeExporter;
use BadChoice\Reports\Exporters\HtmlExporter;
use BadChoice\Reports\Exporters\CsvExporter;
use BadChoice\Reports\Exporters\XlsExporter;
use BadChoice\Reports\Filters\Filters;

abstract class ReportExporter
{
    protected $filters;

    abstract protected function getFields();

    public function __construct($filters = null)
    {
        $this->setFilters($filters);
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function getFilter($key)
    {
        if (! $this->filters || ! isset($this->filters->filters()[$key])) {
            return Filters::find($key);
        }
        return $this->filters->filters()[$key];
    }

    public function toHtml($collection)
    {
        return (new HtmlExporter($this->getFields(), $collection))->export()->print();
    }

    public function toXls($collection, $title = "export")
    {
        return (new XlsExporter($this->getFields(), $collection))->export()->download($title);
    }

    public function toCsv($collection, $title = "export")
    {
        return (new CsvExporter($this->getFields(), $collection))->export()->download($title);
    }

    public function toFake($collection)
    {
        return (new FakeExporter($this->getFields(), $collection))->export();
    }
}
