<?php

namespace BadChoice\Reports\Filters;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait DateFiltersTrait
{
    public function date($date = null)
    {
        if (! $this->dateField) {
            return $this->builder;
        }
        $date = $date ? : Carbon::today()->toDateString();
        $dt  = Carbon::parse($date . " " . $this->openingTime);
        $dt2 = Carbon::parse($date . " " . $this->openingTime)->addDay();
        return $this->builder->whereBetween($this->dateField, [$dt, $dt2]);
    }

    public function start_date($date = null)
    {
        if (! $this->dateField) {
            return $this->builder;
        }
        $date = $date ? : Carbon::parse('first day of this month')->toDateString();
        $date = Carbon::parse($date . " " . $this->openingTime)->subHours($this->offsetHours);   //Start day at opening time - timezone offsetHours
        return $this->builder->where($this->dateField, '>', $date);
    }

    public function end_date($date = null)
    {
        if (! $this->dateField) {
            return $this->builder;
        }
        $date = $date ? : Carbon::today()->toDateString();
        $date = Carbon::parse($date . " " . $this->openingTime)->addDay()->subHours($this->offsetHours); //End day +1 at opening time - timezone offsetHours
        return $this->builder->where($this->dateField, '<', $date);
    }

    public function dayOfWeek($dayOfWeek = null)
    {
        if (! $this->dateField || ! $this->validDayOfWeek($dayOfWeek)) {
            return $this->builder;
        }
        $whereMethod = is_array($dayOfWeek) ? "whereIn" : "where";
        return $this->builder->$whereMethod(DB::raw("dayofweek(" . $this->rawDateField() . ")"), $dayOfWeek);
    }

    public function validDayOfWeek($dayOfWeek)
    {
        return $dayOfWeek != null && $dayOfWeek > 0 && $dayOfWeek < 8;
    }

    public function start_time($time = null)
    {
        if (! $this->dateField || ! $time) {
            return $this->builder;
        }
        return $this->builder->whereRaw("TIME(DATE_ADD(" . $this->rawDateField() . ", INTERVAL {$this->offsetHours} HOUR)) > '{$time}'");
    }

    public function end_time($time = null)
    {
        if (! $this->dateField || ! $time) {
            return $this->builder;
        }
        return $this->builder->whereRaw("TIME(DATE_ADD(" . $this->rawDateField() . ", INTERVAL {$this->offsetHours} HOUR)) < '{$time}'");
    }
}
