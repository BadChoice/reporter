<?php

namespace BadChoice\Reports\DataTransformer\Transformers;

use BadChoice\Reports\DataTransformer\TransformsRowInterface;

class Link implements TransformsRowInterface {

    public function transformRow($object, $row){
        $class = isset($row['class'])? $row['class'] : "";
        $text  = isset($row['text']) ? $this->getValue($object, $row['text'])  : $this->getValue($object, $row['field']);
        $link  = str_replace("{".$row['field']."}", $this->getValue($object, $row['field']), adminPrefix() . $row['url']);

        if( isset($row['icon']) ){
            return "<a class='{$class}' href='". url($link)."' style='font-size:15px'> ". icon($row['icon']) . "</a>";
        }
        return link_to($link, $text, ["class" => $class] );
    }

    protected function getValue($object, $field){
        return collect(explode('.', $field))->reduce( function ($carry, $item) {
            return isset($carry->$item) ? $carry->$item : "";
        }, $object);
    }

}