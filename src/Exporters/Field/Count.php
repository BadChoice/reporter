<?php

namespace BadChoice\Reports\Exporters\Field;

class Count extends ExportField
{
    public function getValue($row, $protectionXSS = true)
    {
        return count(parent::getValue($row, $protectionXSS));
    }
}