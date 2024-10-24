<?php

namespace App\Service\Reports;

abstract class Reports
{
    protected $reportData = [];

    public function __construct()
    {
        $this->buildReportData();
    }

    public function getReportData()
    {
        return $this->reportData;
    }

    abstract protected function buildReportData();
}
