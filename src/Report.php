<?php

namespace BadChoice\Reports;

use BadChoice\Reports\Filters\DefaultFilters;
use BadChoice\Reports\Exporters\ExcelExporter;
use Carbon\Carbon;

abstract class Report{

    protected $filtersClass     = DefaultFilters::class;
    protected $exportColumns    = [];
    protected $exportTitles     = [];
    protected $totalize         = null;

    protected $exporter;

    public function __construct($filters = null) {
        $this->filters = $filters ? : new $this->filtersClass( request() );
    }

    /**
     * @param $parent_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public abstract function query($parent_id = null);

    public function totalize($key = 'all'){
        $this->totalize = $key;
        return $this;
    }

    public function addFilter($key, $value = null){
        $this->filters->addFilter($key, $value);
        return $this;
    }

    /**
     * @param string $start
     * @param string null $end
     * @return $this
     */
    public function forDates($start, $end = null){
        if($start instanceof Carbon)        $start = $start->toDateString();
        if($end && $end instanceof Carbon)  $end = $end->toDateString();

        $this->filters->addFilter("start_date", $start);
        $this->filters->addFilter("end_date", $end ? : Carbon::parse($start)->addDay()->toDateString() );
        return $this;
    }

    public function getFilters( $parent_id ){
        if($this->totalize) $this->filters->addFilter("totalize", $this->totalize);
        return $this->filters;
    }

    /**
     * @param $exporter ReportExporter
     * @return $this
     */
    public function setExporter($exporter){
        $this->exporter = $exporter;
        return $this;
    }

    public function download($parent_id = null){
//        if(! $this->exporter) $this->exporter = new CSVExporter();
        if(! $this->exporter) $this->exporter = new ExcelExporter();
        return $this->exporter->set(
            $this->query( $parent_id ),
            $this->exportFields,
            array_merge( $this->getTransformDates(), $this->getTransformations() ))
            ->download( $this->getExportName() );
    }

    public function getExportName(){
        $className = rtrim(collect(explode("\\",get_class($this)))->last(),"Report");
        return $className . "-" . $this->filters->filters()["start_date"] . '-' . $this->filters->filters()["end_date"];
    }

    public function getTransformations(){
        return [];
    }

    protected function getTransformDates(){
        return [
            "created_at"    => function($value){ return $this->datetimeTransform($value);},
            "opened"        => function($value){ return $this->datetimeTransform($value);},
            "closed"        => function($value){ return $this->datetimeTransform($value);},
            "canceled"      => function($value){ return $this->datetimeTransform($value);},
            "order.opened"  => function($value){ return $this->datetimeTransform($value);},
            "order.closed"  => function($value){ return $this->datetimeTransform($value);},
        ];
    }

    private function datetimeTransform($value){
        return Carbon::parse($value)->timezone( auth()->user()->timezone)->toDatetimeString();
    }
}