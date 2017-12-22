<?php

namespace BadChoice\Reports\Filters;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DefaultFilters extends QueryFilters
{
    use DateFiltersTrait;

    /*
    |---------------------------------------------------------------------
    | DATE FIELD
    |---------------------------------------------------------------------
    | Set the desired field to check the dates against
    | Set it as null if no date filters should apply
    |
    */
    protected $dateField    = 'created_at';
    private $rawDateField   = null;

    /*
    |---------------------------------------------------------------------
    | TABLE
    |---------------------------------------------------------------------
    | Specify the default table to match the wheres with
    */
    protected $table = null;

    /*
    |---------------------------------------------------------------------
    | VALID SORT FIELDS
    |---------------------------------------------------------------------
    | Define the valid sort fields (leave null to not apply it)
    | To make it work at least one key needs to be set
    | example: ['id' => 'asc', 'guests', 'average', 'sum' => 'asc']
    */
    public $validSortFields = null;

    // If set, it will sort with this key when no sort key is defined (in valid sort fields)
    public $defaultSort;

    /*
    |---------------------------------------------------------------------
    | VALID TOTALIZE
    |---------------------------------------------------------------------
    | Define the valid totalize keys
    | To make it work at least one key needs to be set
    */
    public $validTotalize   = null;

    // If set, it will totalize with this key when no totalize key is in request
    public $defaultTotalize = null;

    /*
    |---------------------------------------------------------------------
    | OPENING TIME / TIMEZONE
    |---------------------------------------------------------------------
    | The variables used to query the dates for the business opening time
    | and Timezones
    |
    */
    protected $openingTime;
    protected $offsetHours;

    /**
     * We get the opening time so we can use it in all filters time/date related
     * OrderFilters constructor.
     * @param null $request
     */
    public function __construct($request = null)
    {
        parent::__construct($request);
        $this->openingTime = $this->getOpeningTime();
        $this->offsetHours = Carbon::now(auth()->user()->timezone)->offsetHours;
    }

    public function getOpeningTime()
    {
        return "00:00:00";
    }

    //====================================================================

    /**
     * Sorts the result by the key and order
     *
     * @param $key
     * @param string $order
     * @return mixed
     */
    public function sort($key, $order = 'desc')
    {
        if ($this->validSortFields == null || count($this->validSortFields) > 0) {
            if (array_key_exists($key, $this->validSortFields) || in_array($key, $this->validSortFields)) {
                if (isset($this->validSortFields[$key])) {
                    $order = $this->validSortFields[$key];
                }
                return $this->builder->orderBy($key, $order);
            }
        }
    }

    /**
     * @param $filters
     * @return mixed
     */
    protected function addDefaultFilters($filters)
    {
        if ($this->defaultSort != null) {
            if (! array_key_exists("sort", $filters) || $filters['sort'] == null) {
                $filters["sort"] = $this->defaultSort;
            }
        }
        if ($this->defaultTotalize != null) {
            if (! array_key_exists("totalize", $filters) || $filters['totalize'] == null) {
                $filters["totalize"] = $this->defaultTotalize;
            }
        }
        return $filters;
    }

    /**
     * Does a basic where joining the default tablename
     * @param $key
     * @param $value
     * @return $this
     */
    protected function where($key, $value, $comparison = "=")
    {
        if ($value == null) {
            return $this->builder;
        }
        if (is_array($value)) {
            return $this->builder->whereIn($this->composedKey($key), $value);
        }
        return $this->builder->where($this->composedKey($key), $comparison, $value);
    }

    private function composedKey($key)
    {
        if ($this->table != null) {
            $key = $this->table .'.'.$key;
        }
        return $key;
    }

    protected function hasJoin($table)
    {
        $joins = $this->builder->getQuery()->joins;
        if (! $joins) {
            return false;
        }
        return in_array($table, $joins);
    }

    public function rawDateField()
    {
        if ($this->rawDateField) {
            return $this->rawDateField;
        }
        $this->rawDateField = str_contains($this->dateField, '.') ? DB::connection()->getTablePrefix() . $this->dateField : $this->dateField;
        return $this->rawDateField;
    }
}
