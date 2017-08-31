<?php

namespace BadChoice\Reports\Filters;

use Carbon\Carbon;

trait DateFiltersTrait{

    public function date($date){
        if( ! $this->dateField) return $this->builder;
        $dt  = Carbon::parse($date . " " . $this->openingTime );
        $dt2 = Carbon::parse($date . " " . $this->openingTime )->addDay();
        return $this->builder->whereBetween($this->dateField, [$dt, $dt2]);
    }

    public function start_date($date){
        if( ! $this->dateField) return $this->builder;
        $date = Carbon::parse($date . " " . $this->openingTime)->subHours($this->offsetHours);   //Start day at opening time - timezone offsetHours
        return $this->builder->where($this->dateField, '>', $date);
    }

    public function end_date($date){
        if( ! $this->dateField) return $this->builder;
        $date = Carbon::parse($date . " " . $this->openingTime)->addDay()->subHours($this->offsetHours); //End day +1 at opening time - timezone offsetHours
        return $this->builder->where($this->dateField, '<', $date);
    }

    public function dayofweek($dayofweek = null){
        if( ! $this->dateField) return $this->builder;
        if ($dayofweek != null && $dayofweek > 0 && $dayofweek < 8) {
            return $this->builder->where(DB::raw('dayofweek(' . $this->dateField . ')'), '=', $dayofweek);
        }
    }

    public function start_time($time = "00:00"){
        if( ! $this->dateField) return $this->builder;
        return $this->builder->whereRaw("TIME(DATE_ADD(" . $this->dateField . ", INTERVAL " . $this->offsetHours . " HOUR)) > '" . $time . "'");
    }

    public function end_time($time = "23:59"){
        if( ! $this->dateField) return $this->builder;
        return $this->builder->whereRaw("TIME(DATE_ADD(" . $this->dateField . ", INTERVAL " . $this->offsetHours . " HOUR)) < '" . $time . "'");
    }
}