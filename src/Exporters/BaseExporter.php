<?php

namespace BadChoice\Reports\Exporters;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseExporter
{
    protected $fields;
    protected $collection;
    protected $query;

    abstract protected function init();

    abstract protected function generate();

    abstract protected function finalize();

    abstract protected function getType();

    public function __construct($fields, $collection)
    {
        $this->fields       = collect($fields);
        if ($collection instanceof Builder) {
            $this->query = $collection;
        } else {
            $this->collection = $collection;
        }
    }

    public function export()
    {
        $this->init();
        $this->generate();
        $this->finalize();
        return $this;
    }

    protected function getExportFields()
    {
        return $this->fields->reject(function ($exportfield) {
            return $exportfield->shouldIgnore || in_array($this->getType(), $exportfield->exportExceptTypes);
        })->filter(function ($exportfield) {
            return count($exportfield->exportOnlyTypes) == 0 || in_array($this->getType(), $exportfield->exportOnlyTypes);
        });
    }

    protected function forEachRecord($callback)
    {
        if ($this->collection) {
            return $this->foreachCollectionItem($this->collection, $callback);
        }
        $this->query->chunk(200, function ($collection) use ($callback) {
            $this->foreachCollectionItem($collection, $callback);
        });
    }

    private function foreachCollectionItem($collection, $callback)
    {
        $collection->each(function ($row) use ($callback) {
            $callback($row);
        });
    }
}
