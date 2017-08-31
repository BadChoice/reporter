<?php

namespace BadChoice\Reports\Exporters\Old;

use Maatwebsite\Excel\Facades\Excel;

class ExcelExporter Extends BaseExporter implements ReportExporter {

    public function download( $name ) {
        return $this->fromQuery($name);
    }

    public function fromQuery($name){
        $file   = $this->createTempfile($name);
        $excel  = $this->fillExcel($file);
        unlink( $file["full"] );
        $excel->download('xlsx');
    }

    public function fromCollection(){

    }

    private function createTempFile($name){
        return Excel::create(auth()->user()->tenant . "-" . $name, function($excel) {
            $excel->sheet('report', function($sheet) {});
        })->store('xls', false, true);
    }

    private function fillExcel($file){
        return Excel::load($file["full"], function($excel){
            $excel->sheet('report', function($sheet){
                $this->writeHeader($sheet);
                $rowPointer = 2;
                $this->parseQuery(function($newRow) use($sheet, &$rowPointer){
                    $this->writeRecordToSheet($rowPointer, $newRow, $sheet);
                    $rowPointer++;
                });
            });
        });
    }

    private function writeHeader($sheet){
        $letter = "A";
        foreach($this->fields as $rowName){
            $sheet->setCellValue($letter++ . 1, $rowName);
        }
    }

    private function writeRecordToSheet($rowPointer, $record, $sheet){
        $letter = "A";
        foreach($record as $key => $value){
            $sheet->setCellValue($letter++ . $rowPointer, $value);
        }
    }
}