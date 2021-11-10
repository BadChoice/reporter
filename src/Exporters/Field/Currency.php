<?php

namespace BadChoice\Reports\Exporters\Field;

class Currency extends ExportField
{
    public function getValue($row, $protectionXSS = true)
    {
        return currency(parent::getValue($row, $protectionXSS));
    }
}