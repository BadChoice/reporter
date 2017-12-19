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
        $this->url   = $this->findBaseUrl($url);
        $this->query = $this->findQuery($url);
    }

    protected function findBaseUrl($url)
    {
        $url_components = parse_url($url);
        return $url_components["scheme"] . "://" . $url_components["host"] . ($url_components["path"] ?? '');
    }

    protected function findQuery($url)
    {
        $url_components = parse_url($url);
        if (! isset($url_components["query"]) || empty($url_components["query"])) {
            return [];
        }
        parse_str($url_components["query"], $queryArray);    //Converts a=b&c=d to [a => “b”, c => “d”]
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
        return implode("&", array_map(function ($key, $value) {
            return "{$key}={$value}";
        }, array_keys($this->query), array_values($this->query)));
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
}
