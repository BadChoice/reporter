<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsValueInterface;

class DayOfWeek implements TransformsValueInterface
{
    public function transform($value){
        $dayOfWeek = $value->timezone( auth()->user()->timezone )->dayOfWeek;
        $dayOfWeekName =  date('l', strtotime("Sunday + $dayOfWeek Days"));
        return trans('admin.'.$dayOfWeekName);
    }
}