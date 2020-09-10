<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\DataTransformers\Transformers\Currency;
use BadChoice\Reports\DataTransformers\Transformers\Decimal;
use BadChoice\Reports\DataTransformers\Transformers\SheetDecimal;
use PHPUnit\Framework\Assert;

class FakeExporter extends BaseExporter
{
    public $headers;
    public $rows;
    public static $export_type = 'csv';

    public function __construct($fields, $collection)
    {
        parent::__construct($fields, $collection);
        app()->bind(Decimal::class, SheetDecimal::class);
        app()->bind(Currency::class, Decimal::class);
    }

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
            Assert::assertStringContainsString(strtolower($titles), strtolower($this->headers), "The header doesn't contains {$titles}");
            return;
        }
        collect($titles)->each(function ($title) {
            Assert::assertStringContainsString(strtolower($title), strtolower($this->headers), "The header doesn't contains {$title}");
        });
    }

    public function assertDoesNotHaveHeaders($titles)
    {
        if (is_string($titles)) {
            Assert::assertStringNotContainsString(strtolower($titles), strtolower($this->headers), "The header contains {$titles}");
            return;
        }
        collect($titles)->each(function ($title) {
            Assert::assertStringNotContainsString(strtolower($title), strtolower($this->headers), "The header contains {$title}");
        });
    }

    public function assertRowIs($rowNumber, $key, $value)
    {
        Assert::assertEquals($value, $this->rows[$rowNumber][$key]);
    }

    public function assertRowsCount($count)
    {
        Assert::assertCount($count, $this->rows);
    }
}
