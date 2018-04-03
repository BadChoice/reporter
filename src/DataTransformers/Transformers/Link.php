<?php

namespace BadChoice\Reports\DataTransformers\Transformers;

use BadChoice\Reports\DataTransformers\TransformsRowInterface;

class Link implements TransformsRowInterface
{
    public function transformRow($field, $row, $value, $transformData)
    {
        $class = "";
        $text  = $value;
        $link  = $this->parseLink($row, $transformData, $value);
        if (is_array($transformData)) {
            $class = $transformData['class'] ?? "";
            if (isset($transformData['icon'])) {
                return "<a class='{$class}' href='" . url($link) . "' style='font-size:15px'> " . icon($transformData['icon']) . "</a>";
            }
            $text = $this->getDisplayText($transformData, $value);
        }
        return "<a class='{$class}' href='" . url($link) . "'>{$text}</a>";
    }

    /**
     * @param $row
     * @param $link
     * @param $value
     * @return mixed
     */
    public function parseLink($row, $link, $alternativeValue = null)
    {
        $link           = is_array($link) ? $link['url'] : $link;
        $linkVariables  = null;
        $variablesCount = preg_match_all("/{([|,a-z,A-Z,0-9,_,-,\.]*)}/", $link, $linkVariables);
        return collect($linkVariables[0])->reduce(function($link, $variable) use ($row, $alternativeValue) {
            return $this->updateLinkWith($variable, $row, $link, $alternativeValue);
        }, $link);
    }

    private function updateLinkWith($variable, $row, $link, $alternativeValue)
    {
        return str_replace($variable, 
                           data_get($row, $this->getVariableName($variable, $row), $alternativeValue), 
                           $link);
    }

    private function getVariableName($variable, $row)
    {
        return $this->getAvailableVariableNames($variable)->first(function ($possibleVariable) use ($row) {
            return data_get($row, $possibleVariable) != null;
        }) ? : -1;
    }

    private function getAvailableVariableNames($variable){
        return collect( explode('||', substr($variable, 1, -1)));
    }

    protected function getDisplayText($transformData, $value)
    {
        if (isset($transformData['textCallback'])) {
            return $transformData['textCallback']($value);
        }
        return ($transformData['content'] ?? "") . ($transformData['text'] ?? $value);
    }
}
