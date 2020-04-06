<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\DataTransformers\Transformers\Currency;
use BadChoice\Reports\DataTransformers\Transformers\Decimal;
use BadChoice\Reports\DataTransformers\Transformers\SheetDecimal;
use BadChoice\Reports\Exports\BaseExport;
use Maatwebsite\Excel\Facades\Excel;

class XlsExporter extends BaseExporter
{
    private $file;

    public function __construct($fields, $collection)
    {
        parent::__construct($fields, $collection);
        app()->bind(Decimal::class, SheetDecimal::class);
        app()->bind(Currency::class, Decimal::class);
    }

    public function download($title)
    {
        return Excel::download($this->export, "{$title}.xlsx");
//        return Storage::download($this->file, "{$title}.xlsx");
    }

    public function save($filename)
    {
//        return $this->excel->setFilename($filename)->save();
    }

    public function init()
    {
        $this->file = str_random(25) . '.xlsx';
//        Excel::store(new BaseExport, $this->file);
//            ->store('xls', false, true);
    }

    public function finalize()
    {
//        Storage::delete($this->file);
//        unlink(public_path() . '/tenants/' . $this->file);
//        unlink(storage_path() . '/' . $this->file);
    }

    public function generate()
    {
        $this->export = new BaseExport($this->collection, $this->getExportFields());
//        Excel::store(new BaseExport($this->collection, $this->getExportFields()), $this->file);
    }

    protected function getType()
    {
        return 'xlsx';
    }
}
