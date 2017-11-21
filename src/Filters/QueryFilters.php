<?php

namespace BadChoice\Reports\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilters
{
    protected $builder;
    private $filters;

    public function __construct()
    {
        $this->filters = new Filters;
    }

    /**
     * @param  Builder $builder
     * @param  boolean $totals set it to true to totalize without grouping
     * @return Builder
     */
    public function apply(Builder $builder, $totals = false)
    {
        return $this->applyFromArray($builder, $this->filters(), $totals);
    }

    /**
     * @param  Builder $builder
     * @param array $filters the filters array in form key, value
     * @param  boolean $totals set it to true to totalize without grouping
     * @return Builder
     */
    public function applyFromArray(Builder $builder, array $filters, $totals = false)
    {
        $this->builder = $builder;
        if (method_exists($this, 'globalFilter')) {
            $this->globalFilter();
        }
        collect($filters)->each(function ($value, $name) use ($totals){
            if (! method_exists($this, $name)) {
                return true;
            }
            if ($totals && $name == 'totalize') {
                return $this->$name('all');
            }
            if ($value) {
                return $this->$name($value);
            }
            return $this->$name();
        });
        return $this->builder;
    }

    /**
     * Get all session & request filters data (saves them all to session['filters']
     * @return array
     */
    public function filters()
    {
        return $this->addDefaultFilters($this->filters->get());
    }

    public function clearFilters()
    {
        $this->filters->clear();
    }

    public function addFilter($filter, $value = null)
    {
        $this->filters->add($filter, $value);
        return $this;
    }

    public function with($filter, $value = null)
    {
        return $this->addFilter($filter, $value);
    }

    /**
     * @return mixed
     */
    abstract protected function addDefaultFilters($filters);
}
