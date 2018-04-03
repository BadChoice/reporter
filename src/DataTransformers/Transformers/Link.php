<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class Link implements TransformsRowInterface
{
    private $row;
    private $defaultValue;

    public function transformRow($field, $row, $value, $transformData)
    {
        $class              = "";
        $link               = $this->setRow($row, $value)->parseLink($transformData);
        $text               = $this->value;
        if (is_array($transformData)) {
            $class = $transformData['class'] ?? "";
            if (isset($transformData['icon'])) {
                return "<a class='{$class}' href='" . url($link) . "' style='font-size:15px'> " . icon($transformData['icon']) . "</a>";
            }
            $text = $this->getDisplayText($transformData);
        }
        return "<a class='{$class}' href='" . url($link) . "'>{$text}</a>";
    }

    public function setRow($row, $defaultValue = null)
    {
        $this->row          = $row;
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @param $link
     * @return mixed
     */
    public function parseLink($link)
    {
        $link           = is_array($link) ? $link['url'] : $link;
        $linkVariables  = null;
        $variablesCount = preg_match_all("/{([|,a-z,A-Z,0-9,_,-,\.]*)}/", $link, $linkVariables);
        return collect($linkVariables[0])->reduce(function($link, $variable) {
            return $this->updateLinkWith($variable, $link);
        }, $link);
    }

    private function updateLinkWith($variable, $link)
    {
        return str_replace($variable, 
                           data_get($this->row, $this->getVariableName($variable), $this->defaultValue), 
                           $link);
    }

    private function getVariableName($variable)
    {
        return $this->getAvailableVariableNames($variable)->first(function ($possibleVariable) {
            return data_get($this->row, $possibleVariable) != null;
        }, -1);
    }

    private function getAvailableVariableNames($variable){
        return collect( explode('||', substr($variable, 1, -1)));
    }

    protected function getDisplayText($transformData)
    {
        if (isset($transformData['textCallback'])) {
            return $transformData['textCallback']($this->defaultValue);
        }
        return ($transformData['content'] ?? "") . ($transformData['text'] ?? $this->defaultValue);
    }
}
