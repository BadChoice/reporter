<?php

namespace BadChoice\Reports\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilters{

    protected $request;
    protected $builder;

    private   $filters;
    /**
     * Extra filters that won't be saved into session
     * @var array
     */
    protected $extraFilters = [];

    public function __construct($request = null) {
        $this->request = $request;
    }


    /**
     * @param  Builder $builder
     * @param  boolean $totals set it to true to totalize without grouping
     * @return Builder
     */
    public function apply(Builder $builder, $totals = false) {
        return $this->applyFromArray($builder,$this->filters(),$totals);
    }

    /**
     * @param  Builder $builder
     * @param array $filters the filters array in form key, value
     * @param  boolean $totals set it to true to totalize without grouping
     * @return Builder
     */
    public function applyFromArray(Builder $builder, array $filters, $totals = false) {
        $this->builder = $builder;

        if(method_exists($this,'globalFilter')){
            $this->globalFilter();
        }
        foreach ($filters as $name => $value) {
            if (! method_exists($this, $name))  {  continue;                }
            if ($totals && $name == 'totalize') {  $this->$name('all');     }
            else if (strlen($value))            {  $this->$name($value);    }
            else                                {  $this->$name();           }
        }
        return $this->builder;
    }

    /**
     * Allows you to get the filters with QueryFilters->getFilters()
     * @param $request
     * @return array
     */
    private function getFilters($request){
        $sessionFilters = session('filters') ?? [];
        $filters = $request ? $request->all() : [];
        $filters        = ($sessionFilters) ? array_merge( $sessionFilters, $filters) : $filters;
        session(['filters' => array_only($filters, ["start_date", "end_date"])]);
        return array_merge( $filters, $this->extraFilters );
        //        $sessionFilters = session('filters') ?? [];
        //        $filters        = $request ? $request->all() : [];
        //        $filters = array_merge(array_only($sessionFilters, ["start_date", "end_date"]), $filters);
        //        session(compact('filters'));
        //        return $filters;
    }

    /**
     * Get all session & request filters data (saves them all to session['filters']
     * @return array
     */
    public function filters() {
        return $this->addDefaultFilters($this->getFilters($this->request));
        //        if( ! $this->filters ) {
        //            $filters = array_merge($this->getFilters($this->request), $this->extraFilters);
        //            $this->filters = $this->addDefaultFilters($filters);
        //        }
        //        return $this->filters;
    }

    public function clearFilters() {
        session()->forget('filters');
    }

    /**
     * Add an extra filter that won't be saved to session (for example to do a custom totalize)
     * Products report uses it for the itemsTotal that is not really a filter the uses choses, sinc it is
     * always totalized by items
     *
     * @param $filter
     * @param null $value
     * @return $this
     */
    public function addFilter($filter, $value = null) {
        $this->extraFilters[$filter] = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    abstract protected function addDefaultFilters($filters);
}