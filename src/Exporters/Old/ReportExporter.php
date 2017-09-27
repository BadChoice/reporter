<?php

namespace BadChoice\Reports\Exporters\Old;

interface ReportExporter {
    public function download( $name );
}