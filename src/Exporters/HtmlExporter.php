<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\Filters\Filters;
use BadChoice\Reports\Utils\QueryUrl;

class HtmlExporter extends BaseExporter
{
    protected $output           = "";
    public static $tableClasses = "tableList striped";

    public function print()
    {
        return $this->output;
    }

    protected function init()
    {
        $this->output .= "<table class='" . static::$tableClasses . "'>";
    }

    protected function finalize()
    {
        $this->output .= "</table>";
    }

    protected function generate()
    {
        $this->addHeader();
        $this->addBody();
    }

    protected function addHeader()
    {
        $params = http_build_query(Filters::all());
        $this->output .= $this->getExportFields()->reduce(function ($carry, $field) use ($params) {
            $classes = $field->hideMobile ? "hide-mobile" : "";
            if ($field->isNumeric())  {
                $classes = "{$classes} text-right";
            }
            if (! $field->sortable) {
                return $carry . "<th class='{$classes}'>{$field->getTitle()}</th>";
            }
            $url = QueryUrl::addQueryToUrl(request()->url() . "?{$params}", ["sort" => $field->sortable !== true ? $field->sortable : $field->field]);
            return $carry . "<th class='{$classes}'><div class='sortableHeader'>{$field->getTitle()}<div class='sortArrows'><a href='{$url}&sort_order=desc' class='sortUp'>▲</a><a href='{$url}&sort_order=asc' class='sortDown'>▼</a></div></div></th>";
        }, "<thead class='sticky'><tr>");
        $this->output .= "</tr></thead>";
    }

    protected function addBody()
    {
        $this->output .= "<tbody>";
        $this->forEachRecord(function ($row) {
            $this->output .= "<tr>";
            foreach ($this->getExportFields() as $field) {
                $classes = $field->hideMobile ? "hide-mobile" : "";
                $value = $field->getValue($row);
                if ($field->isNumeric() || is_numeric($value))  {
                    $classes = "{$classes} text-right";
                }
                $this->output .= "<td class='{$classes}'>{$value}</td>";
            }
            $this->output .= "</tr>";
        });
        $this->output .= "</tbody>";
    }

    protected function getType()
    {
        return "html";
    }
}
