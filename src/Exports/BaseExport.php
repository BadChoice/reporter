<?php


namespace BadChoice\Reports\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BaseExport implements WithTitle, WithMapping, FromQuery, WithHeadings, WithEvents
{
    protected $query;
    private $mapping;

    public function __construct($query, $mapping)
    {
        $this->query   = $query;
        $this->mapping = $mapping;
    }

    public function title(): string
    {
        return 'Report Sheet';
    }

    public function headings(): array
    {
        return $this->mapping->map(function ($exportField) {
            return $exportField->getTitle();
        })->toArray();
    }

    public function query()
    {
        return $this->query;
    }

    public function stringToFloat($value): float
    {
        return floatval(str_replace(',', '.', str_replace('.', '', $value)));
    }

    public function map($product): array
    {
        return $this->mapping->map(function ($exportField) use ($product) {
            if ($exportField->isPercentage()) {
                return $this->stringToFloat($exportField->getValue($product)) / 100.0;
            }
            if ($exportField->isNumeric()) {
                return $this->stringToFloat($exportField->getValue($product));
            }
            return $exportField->getValue($product);
        })->toArray();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $sheet->freezePane('A2');  // no se perquè va
                $letter = "A";
                $this->mapping->each(function ($field) use (&$letter, $sheet) {
                    if ($field->isNumeric()) {
                        $sheet->getDelegate()->getStyle("{$letter}2:{$letter}{$sheet->getHighestDataRow()}")
                            ->getNumberFormat()
                            ->applyFromArray([
                                'formatCode' => $field->isPercentage() ? NumberFormat::FORMAT_PERCENTAGE_00 : NumberFormat::FORMAT_NUMBER_00
                            ]);
                    }
                    ++$letter;
                });

                $sheet->getDelegate()->getStyle("A1:{$sheet->getHighestDataColumn()}1")
                    ->applyFromArray($this->getStyleHeaderArray());
            },
        ];
    }

    public function getStyleHeaderArray(): array
    {
        return [
            'font'    => [
                'bold'  => true,
                'size'  => 12,
                'name'  => 'Calibri',
                'color' => ['argb' => 'ea5b2e'],
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill'    => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '282223',
                ],
            ],
        ];
    }
}
