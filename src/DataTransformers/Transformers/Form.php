<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class Form extends Link implements TransformsRowInterface
{
    public function transformRow($field, $row, $value, $transformData)
    {
        $link        = $this->setRow($row, $value)->parseLink($transformData);
        $method      = is_array($transformData) ? $transformData["method"] ?? "POST" : "POST";
        $text        = is_array($transformData) ? $transformData["text"] ?? __('admin.save') : __('admin.save');
        $class       = is_array($transformData) ? $transformData["class"] ?? '' : '';
        $attributes  = is_array($transformData) ? $transformData['attributes'] ?? '' : '';
        return "<form action='{$link}' method='POST'>".
            "<input name='_method' type='hidden' value='{$method}'>" .
            "<input type='hidden' name='_token' value='" . csrf_token() . "'>" .
            "<input {$attributes} class='{$class}' name='{$field}' value='{$row->$field}'/> ".
            "<button class='secondary' style='margin-top:-1;min-width: 10px'>" . $text . "</button>".
            "</form>";
    }
}
