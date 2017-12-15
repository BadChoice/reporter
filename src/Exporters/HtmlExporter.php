<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\Filters\Filters;

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
            if (! $field->sortable) {
                return $carry . "<th class='{$classes}'>{$field->getTitle()}</th>";
            }
            $url = $this->addQueryToUrl(request()->url() . "?{$params}", ["sort" => $field->field]);
            return $carry . "<th class='{$classes}'><a href='{$url}'>{$field->getTitle()}</a></th>";
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
                $this->output .= "<td class='".$classes."'>{$field->getValue($row)}</td>";
            }
            $this->output .= "</tr>";
        });
        $this->output .= "</tbody>";
    }

    protected function getType()
    {
        return "html";
    }

    private function addQueryToUrl($url, $query)
    {
        $url = url($url);
        if ($query == null) {
            return $url;
        }
        if (is_array($query)) {
            $query = implode("&", array_map( function($value, $key) {
                    return "{$key}=$value";
                }, $query, array_keys($query))
            );
        }
        $url_components = parse_url($url);
        if (empty($url_components['query'])) {
            return $url . '?' . ltrim($query, '?');
        }
        parse_str($url_components['query'], $original_query_string);
        parse_str($query, $merged_query_string);
        $merged_result = array_merge($original_query_string, $merged_query_string);
        return str_replace($url_components['query'], http_build_query($merged_result), $url);
    }
}
