<?php

namespace BadChoice\Reports\Exporters\Old;

use BadChoice\Reports\Exports\Old\BaseExport;
use BadChoice\Reports\Exports\Old\CollectionBaseExport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporter extends BaseExporter implements ReportExporter
{
    public function download($name)
    {
        return Excel::download($this->getExport(), (auth()->user()->tenant ?? auth()->user()->username) . "-{$name}.xlsx");
    }

    protected function getExport()
    {
        if ($this->query instanceof Collection) {
            return new CollectionBaseExport($this->query, $this->fields);
        }
        return new BaseExport($this->query, $this->fields);
    }
}
