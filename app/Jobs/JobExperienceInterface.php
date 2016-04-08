<?php namespace App\Jobs;


/**
 * Feature that may be used within a job
 */
Interface JobExperienceInterface {

    /**
     * Capture started timestamp.
     */
    public function started();

    /**
     * Set Job Class name
     */
    public function setClass($class);

    /**
     * Set numberOfRecords to Process
     */
    public function setNumberOfRecordsProcessed($numberOfRecordsProcessed);

    /**
     * Record job experience
     */
    public function ended();

}
