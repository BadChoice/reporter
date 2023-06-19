<?php

namespace BadChoice\Reports\Exporters\Field;

use BadChoice\Reports\DataTransformers\ReportDataTransformer;

class ExportField
{
    public $field;
    protected $title;

    public $shouldIgnore = false;
    public $hideMobile   = false;
    public $sortable     = false;

    public $exportOnlyTypes      = [];
    public $exportExceptTypes    = [];

    public $transformation;
    public $transformationData;

    public function __construct($field, $title = null)
    {
        $this->field = $field;
        $this->title = $title;
    }

    public static function make($field, $title = null)
    {
        $field = new static($field, $title);
        return $field;
    }

    function ignoreWhenTotalizedExcept($totalize, $except){
        return $this->ignoreWhen($totalize && !collect($except)->contains($totalize));
    }

    public function ignoreWhen($shouldIgnore)
    {
        $this->shouldIgnore = $shouldIgnore;
        return $this;
    }

    /**
     * @param $transformation string|array the name of the transformation or array of transformation names
     * @param null $transformationData object the data to be applied to the transformation. Note only available when using string as transformation (not working on array)
     * @return $this
     */
    public function transform($transformation, $transformationData = null)
    {
        $this->transformation       = $transformation;
        $this->transformationData   = $transformationData;
        return $this;
    }

    public function hideMobile($shouldHide = true)
    {
        $this->hideMobile = $shouldHide;
        return $this;
    }

    public function sortable($sortable = true)
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function only($type)
    {
        if (is_array($type)) {
            $this->exportOnlyTypes = array_merge($this->exportOnlyTypes, $type);
        } else {
            $this->exportOnlyTypes[] = $type;
        }
        return $this;
    }

    public function except($type)
    {
        if (is_array($type)) {
            $this->exportExceptTypes = array_merge($this->exportExceptTypes + $type);
        } else {
            $this->exportExceptTypes[] = $type;
        }
        return $this;
    }

    public function getTitle()
    {
        return $this->title ? : $this->field;
    }

    public function getValue($row, $protectionXSS = true)
    {
        if(is_array($this->transformation)){
            return $this->transformMany($row);
        }
        $fieldData = data_get($row,$this->field);
        if ($protectionXSS && is_string($fieldData)){
            $fieldData = htmlspecialchars($fieldData);
        }
        return app(ReportDataTransformer::class)::transform($row, $this->field, $fieldData, $this->transformation, $this->transformationData);
    }

    private function transformMany($row)
    {
        $value = data_get($row, $this->field);
        collect($this->transformation)->each(function($transformationData, $transformation) use($row, &$value){
            if (! is_string($transformation)) {
                return $value = app(ReportDataTransformer::class)::transform($row, $this->field, $value, $transformationData);
            }
            $value = app(ReportDataTransformer::class)::transform($row, $this->field, $value, $transformation, $transformationData);
        });
        return $value;
    }

    public function isNumeric()
    {
        return collect($this->transformation)->values()->filter(function ($value) {
            return is_string($value);
        })->intersect(["decimal", "percentage", "currency", "numeric"])->count() > 0;
    }

    public function isPercentage()
    {
        return collect($this->transformation)->values()->filter(function ($value) {
            return is_string($value);
        })->intersect(["percentage"])->count() > 0;
    }
}
