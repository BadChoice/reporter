<?php

namespace BadChoice\Reports\Exporters;

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
    public function set($query, $fields, $transformations = []) {
        $this->query = $query;
        $this->fields = $fields;
        $this->transformations = $transformations;
        return $this;
    }

    public function parseQuery($callback) {
        $this->query->chunk(200, function ($collection) use ($callback) {
            $this->parseCollection($collection, $callback);
        });
    }

    public function parseCollection($collection, $callback) {
        foreach ($collection as $row) {
            $newRow = [];
            foreach ($this->fields as $fieldName) {
                $newRow[$fieldName] = $this->parseRowField($row, $fieldName);
            }
            $callback($newRow);
        }
    }

    /**
     * This functions fetches the right value (with one depth using .) and applies the transformation defined in $transformations for the specific key
     *
     * @param array $row the full row
     * @param string $rowName the name of the field of the row
     * @return string row field transformed
     */
    protected function parseRowField($row, $rowName) {
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
    private function getValueFromField($row, $composedField) {
        $fields = explode('.', $composedField);
        if (count($fields) <= 1) {
            return $row->$composedField;
        } else {
            $value = $row;
            foreach ($fields as $field) {
                if ($value == null) return "-";
                if (is_array($value)) {
                    $value = ($value[$field]) ? $value[$field] : null;
                } else {
                    $value = ($value->$field) ? $value->$field : null;
                }
            }
            return $value;
        }
    }

    protected function convertNumberIfNecessary($value) {
        if (is_numeric($value) && str_contains($value, ".")) {
            return static::commaNumber($value);
        }
        return $value;
    }

    static function commaNumber($number, $decimals = true) {
        if ($decimals) return number_format($number, 2, ',', '.');
        else           return number_format($number, 0, ',', '.');
    }
}