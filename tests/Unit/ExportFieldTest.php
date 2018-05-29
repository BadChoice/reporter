<?php

namespace Tests\Unit;

use BadChoice\Reports\Exporters\Field\ExportField;
use BadChoice\Reports\Exporters\FakeExporter;
use PHPUnit\Framework\TestCase;

class ExportFieldTest extends TestCase
{
    /** @test */
    public function can_export_a_field()
    {
        $collection = collect([["name"   => "Pepito"],["name"   => "Pepita"]]);
        $fields     = [ExportField::make("name", "Name")->transform('text')];

        FakeExporter::setExportType('csv');
        $exporter   = (new FakeExporter($fields, $collection))->export();

        $exporter->assertRowsCount(2);
        $exporter->assertHasHeaders(["Name"]);
        $exporter->assertRowIs(0, "Name", "Pepito");
        $exporter->assertRowIs(1, "Name", "Pepita");
    }

    /** @test */
    public function can_export_fields_with_multiple_transformations()
    {
        $collection = collect([
            ["name"   => "Pepito", "price"  => 12.222],
            ["name"   => "Pepita", "price"  => 10]
        ]);
        $fields     = [
            ExportField::make("name", "Name"),
            ExportField::make("price", "Decimal")->transform('decimal'),
            ExportField::make("price", "Percentage")->transform('percentage'),
            ExportField::make("price", "DecimalPercentage")->transform(['decimal', 'percentage']),
        ];

        FakeExporter::setExportType('csv');
        $exporter   = (new FakeExporter($fields, $collection))->export();

        $exporter->assertRowsCount(2);
        $exporter->assertHasHeaders(["Name", "Decimal", "Percentage", "DecimalPercentage"]);
        $exporter->assertRowIs(0, "Name", "Pepito");
        $exporter->assertRowIs(1, "Name", "Pepita");
        $exporter->assertRowIs(0, "Decimal", "12.22");
        $exporter->assertRowIs(1, "Decimal", "10.00");
        $exporter->assertRowIs(0, "Percentage", "12.22 %");
        $exporter->assertRowIs(1, "Percentage", "10.00 %");
        $exporter->assertRowIs(0, "DecimalPercentage", "12.22 %");
        $exporter->assertRowIs(1, "DecimalPercentage", "10.00 %");
    }

    /** @test */
    public function can_export_fields_with_multiple_transformations_with_arrays()
    {
        $collection = collect([
            ["name"   => "Pepito", "price"  => 12.222],
            ["name"   => "Pepita", "price"  => 10]
        ]);
        $fields     = [
            ExportField::make("name", "Name"),
            ExportField::make("price", "Decimal")->transform('decimal'),
            ExportField::make("price", "Callback")->transform('callback', function ($value) {
                return $value . ' €';
            }),
            ExportField::make("price", "DecimalWithCallback")->transform(['decimal' => 'decimalData', 'callback' => function ($value) {
                return $value . ' €';
            }]),
        ];

        FakeExporter::setExportType('csv');
        $exporter   = (new FakeExporter($fields, $collection))->export();

        $exporter->assertRowsCount(2);
        $exporter->assertHasHeaders(["Name", "Decimal", "Callback", "DecimalWithCallback"]);
        $exporter->assertRowIs(0, "Name", "Pepito");
        $exporter->assertRowIs(1, "Name", "Pepita");
        $exporter->assertRowIs(0, "Decimal", "12.22");
        $exporter->assertRowIs(1, "Decimal", "10.00");
        $exporter->assertRowIs(0, "Callback", "12.222 €");
        $exporter->assertRowIs(1, "Callback", "10 €");
        $exporter->assertRowIs(0, "DecimalWithCallback", "12.22 €");
        $exporter->assertRowIs(1, "DecimalWithCallback", "10.00 €");
    }

}
