<?php

namespace BadChoice\Reports;

use BadChoice\Reports\Exporters\HtmlExporter;
use BadChoice\Reports\Exporters\CsvExporter;
use BadChoice\Reports\Exporters\XlsExporter;

abstract class ReportExporter{

    public $fields;

    public function __construct(){
        $this->fields = $this->getFields();
    }

    public function toHtml($collection){
        return (new HtmlExporter($this->fields, $collection))->export()->print();
    }

    public function toXls($collection){
        return (new XlsExporter($this->fields, $collection))->export()->download();
    }

    public function toCsv($collection){
        return (new CsvExporter($this->fields, $collection))->export()->download();
    }

    protected abstract function getFields();
}