<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\DataTransformers\Transformers\Currency;
use BadChoice\Reports\DataTransformers\Transformers\Decimal;
use BadChoice\Reports\DataTransformers\Transformers\SheetDecimal;
use BadChoice\Reports\Exports\BaseExport;
use Maatwebsite\Excel\Facades\Excel;

class XlsExporter extends BaseExporter
{
    private $exportData;

    public function __construct($fields, $collection)
    {
        parent::__construct($fields, $collection);
        app()->bind(Decimal::class, SheetDecimal::class);
        app()->bind(Currency::class, Decimal::class);
    }

    public function download($title)
    {
        return Excel::download($this->exportData, "{$title}.xlsx");
//        return Storage::download($this->file, "{$title}.xlsx");
    }

    public function save($filename)
    {
        Excel::store($this->exportData, $filename);
    }

    public function init()
    {
    }

    public function finalize()
    {
    }

    public function generate()
    {
        $this->exportData = new BaseExport($this->collection, $this->getExportFields());
    }

    protected function getType()
    {
        return 'xlsx';
    }
}
