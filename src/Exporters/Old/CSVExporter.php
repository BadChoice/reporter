<?php

namespace BadChoice\Reports\Exporters\Old;

use Response;

class CSVExporter extends BaseExporter implements ReportExporter{

    public function download( $name ) {
        return $this->fromQuery( $name );
    }

    /**
     * @param $title
     * @param $raw
     * @return mixed
     */
    static function fromRaw($title, $raw){
        return (new static)->makeResponse($raw,$title);
    }

    /**
     * This functions creates a CSV from a collection, if the collection is too big there will be a memory error (when doing the get before calling this function)
     * eloquent
     * @param $title string Desired output filename
     * @param \Illuminate\Support\Collection $collection the collection (after performing the get)
     * @return mixed
     */
    function fromCollection($title, $collection){
        $output = '';
        $this->writeHeader($output);
        $this->parseCollection($collection, function($newRow) use(&$output){
            $this->writeRow($output, $newRow);
        });
        return $this->makeResponse($output, $title);
    }

    /**
     * This functions uses the chunk method, that basically does small queries so the ram is not eated by
     * eloquent
     *
     * @param $title string output filename
     * @return mixed
     */
    function fromQuery($title){
        $output='';
        $this->writeHeader($output);
        $this->parseQuery(function($newRow) use(&$output){
            $this->writeRow($output, $newRow);
        });
        return $this->makeResponse($output, $title);
    }

    private function writeHeader(&$output){
        foreach($this->fields as $rowName){
            $output.= $rowName . ';';
        }
        $output .= PHP_EOL;
    }

    private function writeRow(&$output, $newRow){
        foreach($newRow as $key => $value){
            $output .=  $value . ';';
        }
        $output .= PHP_EOL;
    }

    private function getHeaders($title){
        return [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$title.'.csv"',
        ];
    }

    private function makeResponse($output, $title){
        return Response::make(rtrim($output, "\n"), 200, $this->getHeaders($title));
    }
}