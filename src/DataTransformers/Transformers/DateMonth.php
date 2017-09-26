<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsValueInterface;
use Carbon\Carbon;

class DateMonth implements TransformsValueInterface {
    public function transform( $value ) {
        if ( ! $value ) {
            return "--";
        }
        if ( ! is_int( $value ) ) {
            $value = Carbon::parse( $value )->timezone( auth()->user()->timezone )->month;
        }
        return getMonthName( $value );
    }
}