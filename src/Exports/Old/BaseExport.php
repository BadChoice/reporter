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
            return $this->getValueFromField($product,$exportField);
        })->toArray();
    }

    private function getValueFromField($row, $composedField)
    {
        $fields = explode('.', $composedField);
        if (count($fields) <= 1) {
            return $row->$composedField;
        }
        $value = $row;
        foreach ($fields as $field) {
            if ($value == null) {
                return "-";
            }
            if (is_array($value)) {
                $value = ($value[$field]) ? $value[$field] : null;
            } else {
                $value = ($value->$field) ? $value->$field : null;
            }
        }
        return $value;
    }
}
