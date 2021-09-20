<?php


namespace BadChoice\Reports\Exports\Old;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BaseExport implements WithTitle, WithMapping, FromQuery, WithHeadings
{
    private $query;
    private $fields;

    public function __construct($query, $fields)
    {
        $this->query    = $query;
        $this->fields   = $fields;
    }

    public function title(): string
    {
        return 'Report Sheet';
    }

    public function headings(): array
    {
        return $this->fields;
    }

    public function query()
    {
        return $this->query;
    }

    public function map($product): array
    {
        return collect($this->fields)->map(function ($exportField) use ($product) {
            return data_get($product, $exportField, "--");
        })->toArray();
    }
}
