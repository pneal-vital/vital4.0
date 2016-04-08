<?php namespace App\Jobs;

use vital3\Repositories\InventoryRepositoryInterface;
use vital40\Repositories\InventorySummaryRepositoryInterface;
use \Log;

/**
 * See: http://laravel.com/docs/5.1/queues
 */
class PopulateInventorySummary extends Job
{
    // already present in App\Job\Job.php we extend
    // implements SelfHandling, ShouldQueue
    //use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct() {
        Log::debug('__construct');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
          InventoryRepositoryInterface $inventoryRepository
        , InventorySummaryRepositoryInterface $inventorySummaryRepository
        , JobExperienceInterface $jobExperience
    ) {
        $jobExperience->setClass($this);

        Log::debug(' attempts: '.$this->attempts());

        // First count the number of UPCs that have Inventory
        $itemCount = $inventoryRepository->countUPCs();
        Log::debug(' Inventory Items count: '.$itemCount);
        $jobExperience->setNumberOfRecordsProcessed($itemCount);

        // divide by 2000 to get number of invocations required
        $invocations = intval($itemCount / 2000 * 1.6) + 1;
        Log::debug(' invocations: '.$invocations);

        $sleepSeconds = 1;
        // run the invocations
        for($i = 0; $i < $invocations; $i++) {
            sleep($sleepSeconds);
            Log::debug(' run: '.$i);
            //$sleepSeconds = 5;
            $inventorySummaryRepository->fireStoredProcedure();
        }

        $jobExperience->ended();
    }

    /**
     * Handle a job failing.
     *
     * @return void
     */
    public function failed() {
        Log::debug('failed!');
    }
}
