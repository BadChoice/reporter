<?php

namespace BadChoice\Reports\Utils;

class QueryUrl
{
    protected $url;
    protected $query;

    public static function addQueryToUrl($url, $query = '')
    {
        return (new static($url))->addQuery($query)->build();
    }

    public function __construct($url)
    {
        $this->url   = $this->getUrlWithoutQuery($url);
        $this->query = $this->findQuery($url);
    }

    protected function getUrlWithoutQuery($url)
    {
        $query = parse_url($url)['query'] ?? "";
        return str_replace(array($query, '?'), '', $url);
    }

    protected function findQuery($url)
    {
        parse_str(parse_url($url)["query"] ?? "", $queryArray);
        return $queryArray;
    }

    public function addQuery($query)
    {
        if (! $query) {
            return $this;
        }
        $this->query = array_merge($this->query, $this->getQueryAsArray($query));
        return $this;
    }

    public function build()
    {
        return $this->url . "?" . $this->generateQueryString();
    }

    protected function generateQueryString()
    {
        return collect($this->query)->map(function($value, $key){
            if (is_array($value)){
                return $this->generateMultipleQueryString($key, $value);
            }
            return "{$key}={$value}";
        })->implode('&');
    }

    /**
     * @param $query
     * @return array
     */
    private function getQueryAsArray($query)
    {
        if (! is_array($query)) {
            parse_str($query, $query);
        }
        return $query;
    }

    private function generateMultipleQueryString($key, $values)
    {
        return collect($values)->map(function($value) use($key){
            return "{$key}[]={$value}";
        })->implode("&");
    }
}