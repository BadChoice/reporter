<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class Link implements TransformsRowInterface {

    public function transformRow($field, $row, $value, $transformData) {
        $class = "";
        $text  = $value;
        $link  = $this->parseLink($row, $transformData);
        if ( is_array($transformData) ) {
            $class = $transformData['class'] ?? "";
            $text  = ($transformData['content'] ?? "") . ($transformData['text'] ?? $value);
            if ( isset($transformData['icon']) ) {
                return "<a class='{$class}' href='" . url($link) . "' style='font-size:15px'> " . icon($transformData['icon']) . "</a>";
            }
        }
        return link_to($link, $text, compact("class"));
    }

    /**
     * @param $row
     * @param $link
     * @return mixed
     */
    private function parseLink($row, $link) {
        $link = is_array($link) ? $link['url'] : $link;
        $matches = null;
        $result = preg_match("/{([a-z,_,-]*)}/", $link, $matches);
        while ( $result ) {
            $link = str_replace($matches[0], $row[$matches[1]], $link);
            $result = preg_match("/{([a-z,_,-]*)}/", $link, $matches);
        }
        return $link;
    }
}