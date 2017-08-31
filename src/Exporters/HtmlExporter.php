<?php

namespace BadChoice\Reports\Exporters;

class HtmlExporter{
    protected $output       = "";
    public    $tableClasses = "tableList striped";

    protected $fields;
    protected $collection;

    public function __construct($fields, $collection){
        $this->fields       = $fields;
        $this->collection   = $collection;
    }

    public function print(){
        $this->initTable    ();
        $this->addHeader    ();
        $this->addBody      ();
        $this->closeTable   ();
        return $this->output;
    }

    private function initTable(){
        $this->output .= "<table class='".$this->tableClasses."'>";
    }

    private function closeTable(){
        $this->output .="</table>";
    }

    public function addHeader(){
        $this->output .= "<thead class='sticky'><tr>";
        foreach( $this->fields as $field){
            if( $field->shouldIgnore ) continue;
            //TODO: add sortable
            $classes = $field->hideMobile ? "hide-mobile" : "";
            if($field->sortable)
                $this->output .= "<th classes='".$classes."'><a href='?sort={$field->field}'>{$field->getTitle()}</a></th>";
            else
                $this->output .= "<th classes='".$classes."'>{$field->getTitle()}</th>";
        }
        $this->output .= "</tr></thead>";
    }

    public function addBody(){
        $this->output .="<tbody>";
        foreach($this->collection as $key => $row) {
            $this->output .= "<tr>";
            foreach($this->fields as $field){
                if( $field->shouldIgnore ) continue;
                $classes = $field->hideMobile ? "hide-mobile" : "";
                $this->output .= "<td class='".$classes."'>{$field->getValue($row)}</td>";
            }
            $this->output .= "</tr>";
        }
        $this->output .="</tbody>";
    }
}