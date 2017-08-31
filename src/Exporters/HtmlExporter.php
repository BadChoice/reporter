<?php

namespace BadChoice\Reports\Exporters;

class HtmlExporter extends BaseExporter{
    protected $output       = "";
    public    $tableClasses = "tableList striped";

    public function print(){
        return $this->output;
    }

    protected function init(){
        $this->output .= "<table class='".$this->tableClasses."'>";
    }

    protected function finalize(){
        $this->output .="</table>";
    }

    protected function generate(){
        $this->addHeader();
        $this->addBody();
    }

    protected function addHeader(){
        $this->output .= "<thead class='sticky'><tr>";
        foreach( $this->getExportFields() as $field){
            $classes = $field->hideMobile ? "hide-mobile" : "";
            if($field->sortable)
                $this->output .= "<th classes='".$classes."'><a href='?sort={$field->field}'>{$field->getTitle()}</a></th>";
            else
                $this->output .= "<th classes='".$classes."'>{$field->getTitle()}</th>";
        }
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
}