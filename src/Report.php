<?php

namespace BadChoice\Reports;

use BadChoice\Reports\Filters\DefaultFilters;
use BadChoice\Reports\Exporters\Old\ExcelExporter;
use Carbon\Carbon;

abstract class Report
{
    public $filters;
    protected $filtersClass     = DefaultFilters::class;
    protected $exportColumns    = [];
    protected $exportTitles     = [];
    protected $totalize         = null;
    protected $withEagerLoading = false;

    protected $exporter;

    protected $reportExporter  = null;

    public function __construct($filters = null)
    {
        $this->filters = $filters ? : app($this->filtersClass, request()->toArray());
    }

    /**
     * @param $parent_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query($parent_id = null);

    public function get($parent_id = null)
    {
        return $this->query($parent_id)->get();
    }

    public function paginate($count, $parent_id = null)
    {
        return $this->query($parent_id)->paginate($count);
    }

    public function chunk($count, $callback, $parent_id = null)
    {
        return $this->query($parent_id)->chunk($count, $callback);
    }

    public function first($parent_id = null)
    {
        return $this->query($parent_id)->first();
    }

    public function totalize($key = 'all')
    {
        $this->totalize = $key;
        return $this;
    }

    public function addFilter($key, $value = null)
    {
        $this->filters->addFilter($key, $value);
        return $this;
    }

    /**
     * @param string $start
     * @param string null $end
     * @return $this
     */
    public function forDates($start, $end = null)
    {
        $start = Carbon::parse($start)->toDateString();
        $this->filters->addFilter('start_date', $start);
        $this->filters->addFilter('end_date', $end ? Carbon::parse($end)->toDateString() : $start);
        return $this;
    }

    public function getFilters($parent_id = null)
    {
        if ($this->totalize) {
            $this->filters->addFilter('totalize', $this->totalize);
        }
        return $this->filters;
    }

    public function getFilter($key)
    {
        return $this->filters->filters()[$key] ?? null;
    }

    /**
     * @param $exporter ReportExporter
     * @return $this
     */
    public function setExporter($exporter)
    {
        $this->exporter = $exporter;
        return $this;
    }

    public function getExporter($filters = false)
    {
        $exporter = app($this->reportExporter);
        if ($filters) {
            $exporter->setFilters($filters);
        }
        return $exporter;
    }

    public function getExportName()
    {
        $className = str_replace('Report', '', (new \ReflectionClass($this))->getShortName());
        if (! isset($this->filters->filters()['start_date']) || ! isset($this->filters->filters()['end_date'])) {
            return $className . '-' . Carbon::today()->toDateString();
        }
        return $className . '-' . $this->filters->filters()['start_date'] . '-' . $this->filters->filters()['end_date'];
    }

    public function export($type = 'xls', $pagination = 50)
    {
        if ($type == 'xls') {
            return $this->getExporter()->toXls($this->query())->download($this->getExportName());
        } elseif ($type == 'html') {
            return $this->getExporter()->toHtml($this->query()->paginate($pagination));
        } elseif ($type == 'fake') {
            return $this->getExporter($this->getFilters())->toFake($this->query()->get());
        }
        return $this->getExporter()->toCsv($this->query())->download($this->getExportName());
    }

    public function toApi($pagination = 50, $appends = [])
    {
        return $this->getExporter()->toApi($this->query()->paginate($pagination)->appends($appends));
    }

    public function getTransformations()
    {
        return [];
    }

    protected function getTransformDates()
    {
        return [
            'created_at'    => function ($value) {
                return $this->datetimeTransform($value);
            },
            'opened'        => function ($value) {
                return $this->datetimeTransform($value);
            },
            'closed'        => function ($value) {
                return $this->datetimeTransform($value);
            },
            'canceled'      => function ($value) {
                return $this->datetimeTransform($value);
            },
            'order.opened'  => function ($value) {
                return $this->datetimeTransform($value);
            },
            'order.closed'  => function ($value) {
                return $this->datetimeTransform($value);
            },
        ];
    }

    private function datetimeTransform($value)
    {
        return Carbon::parse($value)->timezone(auth()->user()->timezone)->toDatetimeString();
    }

    public function withEagerLoading($withEagerLoading = true)
    {
        $this->withEagerLoading = $withEagerLoading;
        return $this;
    }
}
