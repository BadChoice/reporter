<?php

namespace BadChoice\Reports\Exporters\Field;

class Decimal extends ExportField
{
    public static $commaDecimal = false;

    protected $decimals = 2;

    public function withDecimals($decimals = 2)
    {
        $this->decimals = $decimals;
    }

    public function getValue($row, $protectionXSS = true)
    {
        $value = parent::getValue($row, $protectionXSS);
        if (static::$commaDecimal) {
            return number_format($value, $this->decimals, ',', '.');
        }
        return number_format($value, $this->decimals, '.', ',');
    }
}