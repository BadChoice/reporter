<?php

namespace BadChoice\Reports;

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

    protected abstract function getFields();

    protected function getDateTotalizeFields(){
        $totalize = Filters::find("totalize");
        return [
            ExportField::make('opened',         trans_choice("admin.day",1))         ->ignoreWhen(!($totalize == 'day'))->transform('dateDay')->hideMobile()->sortable(),
            ExportField::make('opened',         trans_choice("admin.month",1))       ->ignoreWhen(!($totalize == 'day' || $totalize == 'month'))->transform('dateMonth')->hideMobile()->sortable(),
            ExportField::make('opened',         trans_choice("admin.year",1))        ->ignoreWhen(!($totalize == 'day' || $totalize == 'month'))->transform('dateYear')->hideMobile()->sortable(),
            ExportField::make('opened',         trans_choice("admin.dayofweek",1))   ->ignoreWhen(!($totalize == 'dayofweek'))->transform('dayofweek')->hideMobile()->sortable(),
        ];
    }
}