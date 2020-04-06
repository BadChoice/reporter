<?php

namespace BadChoice\Reports\Exporters\Old;

use Maatwebsite\Excel\Facades\Excel;

class ExcelExporter extends BaseExporter implements ReportExporter
{
    public function download($name)
    {
        $file  = $this->createExcel($name);
        $excel = Excel::load($file['full']);
        $excel->download('xlsx');
        unlink($file["full"]);
    }

    private function createExcel($name)
    {
        // TODO: Això no va.
        return Excel::download(function ($excel) {
            $excel->sheet('report', function ($sheet) {
                $this->writeHeader($sheet);
                $rowPointer = 2;
                $this->forEachRecord(function ($newRow) use ($sheet, &$rowPointer) {
                    $this->writeRecordToSheet($rowPointer, $newRow, $sheet);
                    $rowPointer++;
                });
            });
        }, (auth()->user()->tenant ?? auth()->user()->username) . "-" . $name);
    }

    private function writeHeader($sheet)
    {
        $letter = "A";
        foreach ($this->fields as $rowName) {
            $sheet->setCellValue($letter++ . 1, $rowName);
        }
    }

    private function writeRecordToSheet($rowPointer, $record, $sheet)
    {
        $letter = "A";
        foreach ($record as $key => $value) {
            $sheet->setCellValue($letter++ . $rowPointer, $value);
        }
    }
}
