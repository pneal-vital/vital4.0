<?php namespace App\Jobs;

use Carbon\Carbon;
use vital40\Repositories\JobStatusRepositoryInterface;
use \Log;

/**
 * Feature that may be used within a job
 */
class JobStatus implements JobStatusInterface {
    // already present in App\Job\Job.php we extend
    // implements SelfHandling, ShouldQueue
    //use InteractsWithQueue, SerializesModels;

    protected $jobStatusRepository;
    protected $jobStatusEntry;
    protected $numberOfRecordsProcessed = 0;
    private $jsID = ['name' => 0, 'id' => 0];
    private $started;
    private $attempts;

    /**
     * Create a new job instance.
     */
    public function __construct(JobStatusRepositoryInterface $jobStatusRepository) {
        $this->jobStatusRepository = $jobStatusRepository;

        Log::debug('__construct(..)');
        $this->started = Carbon::now();
    }

    /**
     * Capture started timestamp.
     */
    public function starting($class, $id) {
        $this->started = Carbon::now();
        Log::debug(' -- starting: '.$this->started.', name => '.get_class($class));

        $this->jsID['name'] = get_class($class);
        $this->jsID['id'] = $id;
        if(method_exists($class, 'attempts')) {
            $this->attempts = $class->attempts();
        }
        Log::debug(' jsID['.get_class($class).', '.$id.'], attempts: '.$this->attempts);

        $this->jobStatusEntry = $this->jobStatusRepository->find($this->jsID);
        if(isset($this->jobStatusEntry)) {
            $input = [];
            $input['started'] = $this->started;
            if(isset($this->attempts)) $input['attempts'] = $this->attempts;
            $input['completed'] = null;
            $input['rc'] = null;
            $input['results'] = null;
            $this->jobStatusRepository->update($this->jsID, $input);
            Log::debug(' updated');
        }
    }

    /**
     * Capture the Job completed return code and results
     */
    public function completed($rc, $results) {
        $completed = Carbon::now();
        $jobResults = $results;
        if(is_array($results)) $jobResults = serialize($results);
        Log::debug(' completed: '.$completed.', rc: '.$rc.', results: '.$jobResults);

        $this->jobStatusEntry = $this->jobStatusRepository->find($this->jsID);
        if(isset($this->jobStatusEntry)) {
            $input = [];
            $input['completed'] = $completed;
            $input['rc'] = $rc;
            $input['results'] = $results;
            $this->jobStatusRepository->update($this->jsID, $input);
            Log::debug(' updated');
        }
    }

}
