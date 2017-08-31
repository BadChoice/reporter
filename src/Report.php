<?php

namespace BadChoice\Reports;

use BadChoice\Reports\Filters\DefaultFilters;

class Report{

    protected $filtersClass     = DefaultFilters::class;
    protected $exportColumns    = [];
    protected $exportTitles     = [];

    protected $exporter;
}