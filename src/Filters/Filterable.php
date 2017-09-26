<?php

namespace BadChoice\Reports\Filters;

use Illuminate\Database\Eloquent\Builder;

trait Filterable {
    /**
     * Filter a result set with request variables
     *
     * @param  Builder      $query
     * @param  QueryFilters $filters
     * @param  boolean $totals set it to true to totalize without grouping*
     * @return Builder
     */
    public function scopeFilter($query, QueryFilters $filters, $totals = false) {
        return $filters->apply($query,$totals);
    }

    /**
     * Filter a result set with an array
     * @param $query
     * @param QueryFilters $filters
     * @param array $filtersArray
     * @param  boolean $totals set it to true to totalize without grouping
     * @return mixed
     */
    public function scopeFilterArray($query, QueryFilters $filters, array $filtersArray, $totals = false) {
        return $filters->applyFromArray($query,$filtersArray,$totals);
    }
}