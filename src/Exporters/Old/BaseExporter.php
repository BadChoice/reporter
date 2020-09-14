<?php

namespace BadChoice\Reports\Exporters\Old;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BaseExporter
{
    public $fields;
    public $transformations;
    public $query;

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query to create the CSV (it will be called using chunk to not eat the RAM)
     * @param array $fields array the rows you want in the csv
     * @param $transformations array transformations to apply to each row `"row" => transformation()`
     * @return BaseExporter itself
     */
    public function set($query, $fields, $transformations = [])
    {
        $this->query            = $query;
        $this->fields           = $fields;
        $this->transformations  = $transformations;
        return $this;
    }

    public function forEachRecord($callback)
    {
        if ($this->query instanceof Collection) {
            return $this->foreachCollectionItem($this->query, $callback);
        }
        $this->query->chunk(200, function ($collection) use ($callback) {
            $this->foreachCollectionItem($collection, $callback);
        });
    }

    private function foreachCollectionItem($collection, $callback)
    {
        $collection->each(function ($row) use ($callback) {
            $newRow = collect($this->fields)->mapWithKeys(function ($fieldName) use ($row){
                return [$fieldName => $this->parseRowField($row, $fieldName)];
            });
            $callback($newRow);
        });
    }

    /**
     * This functions fetches the right value (with one depth using .) and applies the transformation defined in $transformations for the specific key
     *
     * @param array $row the full row
     * @param string $rowName the name of the field of the row
     * @return string row field transformed
     */
    protected function parseRowField($row, $rowName)
    {
        $value = $this->getValueFromField($row, $rowName);
        if ($this->transformations && isset($this->transformations[$rowName])) {
            return $this->transformations[$rowName]($value, $row);
        }
        return $this->convertNumberIfNecessary($value);
    }

    /**
     * Gets the value of the field of an specific row, it does a recursive search through . to finally find the right value
     *
     * @param array $row the row
     * @param string $composedField the field we want to retrieve from the row, it can be composed with . so discount.name will return the name of the discount
     * @return mixed
     */
    private function getValueFromField($row, $composedField)
    {
        $fields = explode('.', $composedField);
        if (count($fields) <= 1) {
            return $row->$composedField;
        }
        $value = $row;
        foreach ($fields as $field) {
            if ($value == null) {
                return "-";
            }
            if (is_array($value)) {
                $value = ($value[$field]) ? $value[$field] : null;
            } else {
                $value = ($value->$field) ? $value->$field : null;
            }
        }
        return $value;
    }

    protected function convertNumberIfNecessary($value)
    {
        if (is_numeric($value) && Str::contains($value, ".")) {
            return static::commaNumber($value);
        }
        return $value;
    }

    public static function commaNumber($number, $decimals = true)
    {
        if ($decimals) {
            return number_format($number, 2, ',', '.');
        }
        return number_format($number, 0, ',', '.');
    }
}
