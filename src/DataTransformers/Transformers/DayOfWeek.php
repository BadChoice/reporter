<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class DayOfWeek implements TransformsValueInterface
{
    public function transform($value){
        if( is_integer($value)){
            return dayOfWeekName(Carbon::parse($value)->timezone(auth()->user()->timezone)->dayOfWeek);
        }
        $dayOfWeek      = $value->timezone( auth()->user()->timezone )->dayOfWeek;
        $dayOfWeekName  =  date('l', strtotime("Sunday + $dayOfWeek Days"));
        return trans('admin.'.$dayOfWeekName);
    }
}