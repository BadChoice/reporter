<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class DayOfWeek implements TransformsValueInterface {

    public function transform( $value ) {
        if ( ! $value ) return "--";
        if( ! is_integer( $value ) ) {
            $value = Carbon::parse( $value )->timezone( auth()->user()->timezone )->dayOfWeek;
        }
        return dayOfWeekName( $value );
    }
}