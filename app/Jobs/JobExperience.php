<?php namespace App\Jobs;

use Carbon\Carbon;
use vital40\Repositories\JobExperienceRepositoryInterface;
use \Log;

/**
 * Feature that may be used within a job
 */
class JobExperience implements JobExperienceInterface {

    // already present in App\Job\Job.php we extend
    // implements SelfHandling, ShouldQueue
    //use InteractsWithQueue, SerializesModels;

    protected $jobExperienceRepository;
    protected $numberOfRecordsProcessed = 0;
    private $name = 0;
    private $started;

    /**
     * Create a new job instance.
     */
    public function __construct(JobExperienceRepositoryInterface $jobExperienceRepository) {
        $this->jobExperienceRepository = $jobExperienceRepository;
    }

    /**
     * Capture started timestamp.
     */
    public function started() {
        $this->started = Carbon::now();
        Log::debug(' -- started: '.$this->started);
    }

    /**
     * Set Job Name
     */
    public function setClass($class) {
        if(isset($this->started) == false) $this->started();
        $this->name = is_string($class) ? $class : get_class($class);
        Log::debug(' setClass: '.$this->name);
    }

    /**
     * Set numberOfRecords to Process
     */
    public function setNumberOfRecordsProcessed($numberOfRecordsProcessed) {
        $this->numberOfRecordsProcessed = $numberOfRecordsProcessed;
        Log::debug(' setNumberOfRecordsProcessed: '.$this->numberOfRecordsProcessed);
    }

    /**
     * Record job experience
     */
    public function ended() {
        $elapsed = $this->started->diffInMinutes(Carbon::now());
        Log::debug(' -- elapsed: '.$elapsed);

        $jobExperience = ['name' => $this->name, 'experience' => $this->numberOfRecordsProcessed, 'elapsed' => $elapsed, 'started' => $this->started];
        Log::debug(' this->jobExperienceRepository: '.(isset($this->jobExperienceRepository) == false ? "null" : get_class($this->jobExperienceRepository) ));
        $this->jobExperienceRepository->create($jobExperience);
    }

}
