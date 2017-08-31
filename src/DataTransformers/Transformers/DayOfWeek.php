<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class DayOfWeek implements TransformsValueInterface
{
    public function transform($value){
        if( is_integer($value) ){
            return dayOfWeekName( $value );
        }
        $dayOfWeek      = $value->timezone( auth()->user()->timezone )->dayOfWeek;
        $dayOfWeekName  =  date('l', strtotime("Sunday + $dayOfWeek Days"));
        return trans('admin.'.$dayOfWeekName);
    }
}