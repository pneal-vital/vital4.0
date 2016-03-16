<?php namespace App\Http\Controllers;

/**
 * Class ReworkReportController
 * @package App\Http\Controllers
 */
Interface ReworkReportControllerInterface {

    /**
     * Display a Listing of the resource.
     */
    public function CalculateReport($fromDate, $toDate, $limit = 10);

}
