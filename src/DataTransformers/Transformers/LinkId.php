<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class LinkId implements TransformsRowInterface {

    public function transformRow($field, $row, $value, $transformData){
        if( is_array($transformData) ){
            $class = $transformData['class'] ?? "";
            $text  = $transformData['text']  ?? $value;
            $link  = str_replace("{id}", $row['id'], $transformData['url']);
            if( isset($transformData['icon']) ){
                return "<a class='{$class}' href='". url($link)."' style='font-size:15px'> ". icon($row['icon']) . "</a>";
            }
        }else{
            $class = "";
            $text  = $value;
            $link  = str_replace("{id}", $row['id'], $transformData);
        }
        return link_to($link, $text, ["class" => $class] );
    }
}