<?php

namespace BadChoice\Reports\Exporters;

use BadChoice\Reports\Filters\Filters;

class HtmlExporter extends BaseExporter{
    protected $output       = "";
    public static $tableClasses = "tableList striped";

    public function print(){
        return $this->output;
    }

    protected function init(){
        $this->output .= "<table class='" . static::$tableClasses . "'>";
    }

    protected function finalize(){
        $this->output .="</table>";
    }

    protected function generate(){
        $this->addHeader();
        $this->addBody();
    }

    protected function addHeader(){
        $params = http_build_query(Filters::all());
        $this->output .= $this->getExportFields()->reduce(function ($carry, $field) use ($params) {
            $classes = $field->hideMobile ? "hide-mobile" : "";
            if ( ! $field->sortable ) {
                return $carry . "<th classes='{$classes}'>{$field->getTitle()}</th>";
            }
            return $carry . "<th classes='{$classes}'><a href='?sort={$field->field}&{$params}'>{$field->getTitle()}</a></th>";
        }, "<thead class='sticky'><tr>");
        $this->output .= "</tr></thead>";
    }

    protected function addBody(){
        $this->output .="<tbody>";
        $this->forEachRecord(function($row){
            $this->output .= "<tr>";
            foreach($this->getExportFields() as $field){
                $classes = $field->hideMobile ? "hide-mobile" : "";
                $this->output .= "<td class='".$classes."'>{$field->getValue($row)}</td>";
            }
            $this->output .= "</tr>";
        });
        $this->output .="</tbody>";
    }

    protected function getType(){
        return "html";
    }
}