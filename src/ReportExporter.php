<?php

namespace BadChoice\Reports;

use BadChoice\Reports\Exporters\FakeExporter;
use BadChoice\Reports\Exporters\HtmlExporter;
use BadChoice\Reports\Exporters\CsvExporter;
use BadChoice\Reports\Exporters\XlsExporter;
use BadChoice\Reports\Exporters\Field\ExportField;
use BadChoice\Reports\Filters\Filters;

abstract class ReportExporter{

    public $fields;

    public function __construct(){
        $this->fields = $this->getFields();
    }

    public function toHtml($collection){
        return (new HtmlExporter($this->fields, $collection))->export()->print();
    }

    public function toXls($collection, $title = "export"){
        return (new XlsExporter($this->fields, $collection))->export()->download($title);
    }

    public function toCsv($collection, $title = "export"){
        return (new CsvExporter($this->fields, $collection))->export()->download($title);
    }

    public function toFake($collection){
        return (new FakeExporter($this->fields, $collection))->export();
    }

    protected abstract function getFields();
}