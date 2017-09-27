<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;
use FA;

class QuickView implements TransformsRowInterface {
    public function transformRow($field, $row, $value, $transformData) {
        $link  = str_replace("{" . $field . "}", $value, $transformData);
        return '<a href="' . url($link) . '" class="showPopup" style="font-size:14px; color:black">'. FA::icon('eye') .'</a>';
    }
}