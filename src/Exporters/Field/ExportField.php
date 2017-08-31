<?php

namespace BadChoice\Reports\Exporters\Field;

use BadChoice\Reports\DataTransformers\ReportDataTransformer;

class ExportField{

    public $field;
    protected $title;

    public $shouldIgnore = false;
    public $hideMobile   = false;
    public $sortable     = false;

    public $transformation;
    public $transformationData;

    public function __construct($field, $title = null){
        $this->field = $field;
        $this->title = $title;
    }

    public static function make($field, $title = null){
        $field = new static($field, $title);
        return $field;
    }

    public function ignoreWhen($shouldIgnore){
        $this->shouldIgnore = $shouldIgnore;
        return $this;
    }

    public function transform($transformation, $transformationData = null){
        $this->transformation       = $transformation;
        $this->transformationData   = $transformationData;
        return $this;
    }

    public function hideMobile($shouldHide = true){
        $this->hideMobile = $shouldHide;
        return $this;
    }

    public function sortable($sortable = true){
        $this->sortable = $sortable;
        return $this;
    }

    public function getTitle(){
        return $this->title ? : $this->field;
    }

    public function getValue( $row ){
        return ReportDataTransformer::transform($this->field,
                                                data_get($row, $this->field),
                                                $this->transformation,
                                                $this->transformationData);
    }

}