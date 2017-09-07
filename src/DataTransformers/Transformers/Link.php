<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class Link implements TransformsRowInterface {

    public function transformRow($field, $row, $value, $transformData) {
        if( is_array($transformData) ) {
            $class = $transformData['class'] ?? "";
            $text  = $transformData['text'] ?? $value;
            $link  = str_replace("{".$field."}", $value, $transformData['url']);
            if( isset($transformData['icon']) ) {
                return "<a class='{$class}' href='". url($link)."' style='font-size:15px'> ". icon($row['icon']) . "</a>";
            }
        } else {
            $class = "";
            $text  = $value;
            $link  = str_replace("{".$field."}", $value, $transformData);
        }
        return link_to($link, $text, ["class" => $class] );
    }
}