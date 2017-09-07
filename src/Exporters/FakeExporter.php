<?php
namespace BadChoice\Reports\Exporters;

use PHPUnit_Framework_Assert;

class FakeExporter extends BaseExporter{

    public $fakeType = 'fake';
    public $headers;
    public $rows;

    protected function init()       {    }
    protected function finalize()   {    }

    protected function generate() {
        $this->generateHeader();
        $this->generateRows();
    }


    protected function getType() {
        return $this->fakeType;
    }

    protected function generateHeader() {
        $this->headers = collect($this->getExportFields())->map(function ($exportField) {
            return $exportField->getTitle();
        });
    }

    protected function generateRows() {
        $this->rows = [];
        $this->forEachRecord(function ($row) {
            $this->rows[] = collect( $this->getExportFields() )->mapWithKeys(function ($field) use ($row) {
                return [ $field->getTitle() => $field->getValue($row)];
            });
        });
    }

    public function assertHeadersHas($titles){
        if( is_string($titles) ){
            PHPUnit_Framework_Assert::assertContains($titles, $this->headers);
            return;
        }
        collect($titles)->each(function($title){
            PHPUnit_Framework_Assert::assertContains($title, $this->headers);
        });
    }

    public function assertRowIs($rowNumber, $key, $value){
        PHPUnit_Framework_Assert::assertEquals($value, $this->rows[$rowNumber][$key]);
    }
}