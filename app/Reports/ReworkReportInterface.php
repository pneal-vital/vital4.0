<?php namespace App\Reports;

/**
 * Class ReworkReport
 * @package App\Reports
 */
Interface ReworkReportInterface {

    /**
     * Display a Listing of the resource.
     */
    public function generate($fromDate, $toDate, $limit = 10);

}
