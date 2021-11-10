<?php

namespace BadChoice\Reports\Exporters\Field;

class Percentage extends ExportField
{
    public function getValue($row, $protectionXSS = true)
    {
        return parent::getValue($row, $protectionXSS) . ' %';
    }
}