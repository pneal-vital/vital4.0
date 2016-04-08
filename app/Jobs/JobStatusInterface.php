<?php namespace App\Jobs;


/**
 * Feature that may be used within a job
 */
Interface JobStatusInterface {

    /**
     * Capture started timestamp.
     */
    public function starting($class, $id);

    /**
     * Capture the Job completed return code and results
     */
    public function completed($rc, $results);

}
