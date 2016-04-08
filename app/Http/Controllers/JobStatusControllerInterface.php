<?php namespace App\Http\Controllers;



/**
 * Interface JobStatusControllerInterface
 * @package App\Http\Controllers
 */
interface JobStatusControllerInterface {

    /**
     * Dispatch a new job, to start processing immediately.
     *
     * To achieve; dispatch(new ReworkReportJob($fromDate, $toDate, 'csv'));
     * invoke this method dispatchJob('App\Jobs\ReworkReportJob',[$fromDate, $toDate, 'csv']);
     * @return jobID, this is an array[name, id]
     */
    public function dispatchJob($className, $parameters);

}
