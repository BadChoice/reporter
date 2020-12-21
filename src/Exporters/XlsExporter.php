<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\DataTransformers\Transformers\Currency;
use BadChoice\Reports\DataTransformers\Transformers\Decimal;
use BadChoice\Reports\DataTransformers\Transformers\SheetDecimal;
use BadChoice\Reports\Exports\BaseCollectionExport;
use BadChoice\Reports\Exports\BaseExport;
use Illuminate\Support\Collection;
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
        $export = BaseExport::class;
        if ($this->collection instanceof Collection) {
            $export = BaseCollectionExport::class;
        }
        $this->exportData = new $export($this->collection, $this->getExportFields());
    }

    protected function getType()
    {
        return 'xlsx';
    }
}
