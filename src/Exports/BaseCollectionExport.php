<?php


namespace BadChoice\Reports\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class BaseCollectionExport extends BaseExport implements FromCollection
{
    public function collection()
    {
        return $this->query;
    }
}
