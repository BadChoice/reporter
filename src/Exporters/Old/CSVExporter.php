<?php

namespace BadChoice\Reports\Exporters\Old;

use Response;

class CSVExporter extends BaseExporter implements ReportExporter
{
    public function download($name)
    {
        return $this->fromQuery($name);
    }

    public static function fromRaw($title, $raw)
    {
        return (new static)->makeResponse($raw, $title);
    }

    public function fromCollection($title, $collection)
    {
        $output = '';
        $this->writeHeader($output);
        $this->parseCollection($collection, function ($newRow) use (&$output) {
            $this->writeRow($output, $newRow);
        });
        return $this->makeResponse($output, $title);
    }

    public function fromQuery($title)
    {
        $output='';
        $this->writeHeader($output);
        $this->parseQuery(function ($newRow) use (&$output) {
            $this->writeRow($output, $newRow);
        });
        return $this->makeResponse($output, $title);
    }

    private function writeHeader(&$output)
    {
        foreach ($this->fields as $rowName) {
            $output .= $rowName . ';';
        }
        $output .= PHP_EOL;
    }

    private function writeRow(&$output, $newRow)
    {
        foreach ($newRow as $key => $value) {
            $output .= $value . ';';
        }
        $output .= PHP_EOL;
    }

    private function getHeaders($title)
    {
        return [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$title.'.csv"',
        ];
    }

    private function makeResponse($output, $title)
    {
        return Response::make(rtrim($output, "\n"), 200, $this->getHeaders($title));
    }
}
