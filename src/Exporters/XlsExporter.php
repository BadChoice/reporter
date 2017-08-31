<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\Exporters\BaseExporter2;
use Maatwebsite\Excel\Facades\Excel;

class XlsExporter extends BaseExporter {

    private $file;
    private $excel;

    public function download(){
        $this->excel->download('csv');
    }

    public function init(){
        $name = "hello";
        $this->file = Excel::create( auth()->user()->tenant . "-" . $name, function($excel) {
            $excel->sheet('report', function($sheet) {});
        })->store('xls', false, true);
    }

    public function finalize(){
        unlink( $this->file["full"] );
    }

    public function generate(){
        $this->excel = Excel::load($this->file["full"], function($excel){
            $excel->sheet('report', function($sheet){
                $this->writeHeader($sheet);
                $rowPointer = 2;
                $this->forEachRecord( function($newRow) use($sheet, &$rowPointer) {
                    $this->writeRecordToSheet($rowPointer, $newRow, $sheet);
                    $rowPointer++;
                });
            });
        });
    }

    private function writeHeader($sheet){
        $letter = "A";
        foreach($this->getExportFields() as $field){
            $sheet->setCellValue($letter++ . 1, $field->getTitle() );
        }
    }

    private function writeRecordToSheet($rowPointer, $record, $sheet){
        $letter = "A";
        foreach($this->getExportFields() as $field){
            $sheet->setCellValue($letter++ . $rowPointer, $field->getValue( $record ) );
        }
    }
}