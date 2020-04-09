<?php


namespace BadChoice\Reports\Exports\Old;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class CollectionBaseExport implements WithTitle, WithMapping, FromCollection, WithHeadings
{
    private $collection;
    private $fields;

    public function __construct($collection, $fields)
    {
        $this->collection   = $collection;
        $this->fields       = $fields;
    }

    public function title(): string
    {
        return 'Report Sheet';
    }

    public function headings(): array
    {
        return $this->fields;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function map($product): array
    {
        return collect($this->fields)->map(function ($exportField) use ($product) {
            return data_get($product, $exportField);
        })->toArray();
    }
}
