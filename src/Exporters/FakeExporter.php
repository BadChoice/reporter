<?php

namespace BadChoice\Reports\Exporters;

use PHPUnit_Framework_Assert;

class FakeExporter extends BaseExporter
{
    public $headers;
    public $rows;
    public static $export_type = 'csv';

    public static function setExportType($exportType)
    {
        static::$export_type = $exportType;
    }

    protected function init()
    {
    }

    protected function finalize()
    {
    }

    protected function generate()
    {
        $this->generateHeader();
        $this->generateRows();
    }

    protected function getType()
    {
        return static::$export_type;
    }

    protected function generateHeader()
    {
        $this->headers = collect($this->getExportFields())->map(function ($exportField) {
            return $exportField->getTitle();
        });
    }

    protected function generateRows()
    {
        $this->rows = [];
        $this->forEachRecord(function ($row) {
            $this->rows[] = collect($this->getExportFields())->mapWithKeys(function ($field) use ($row) {
                return [ $field->getTitle() => $field->getValue($row)];
            });
        });
    }

    public function assertHasHeaders($titles)
    {
        if (is_string($titles)) {
            PHPUnit_Framework_Assert::assertContains(strtolower($titles), strtolower($this->headers), "The header doesn't contains {$titles}");
            return;
        }
        collect($titles)->each(function ($title) {
            PHPUnit_Framework_Assert::assertContains(strtolower($title), strtolower($this->headers), "The header doesn't contains {$title}");
        });
    }

    public function assertDoesNotHaveHeaders($titles)
    {
        if (is_string($titles)) {
            PHPUnit_Framework_Assert::assertNotContains(strtolower($titles), strtolower($this->headers), "The header contains {$titles}");
            return;
        }
        collect($titles)->each(function ($title) {
            PHPUnit_Framework_Assert::assertNotContains(strtolower($title), strtolower($this->headers), "The header contains {$title}");
        });
    }

    public function assertRowIs($rowNumber, $key, $value)
    {
        PHPUnit_Framework_Assert::assertEquals($value, $this->rows[$rowNumber][$key]);
    }

    public function assertRowsCount($count)
    {
        PHPUnit_Framework_Assert::assertCount($count, $this->rows);
    }
}
