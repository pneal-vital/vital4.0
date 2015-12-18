<?php

namespace App\Jobs;

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

    protected $inventoryRepository;
    protected $inventorySummaryRepository;

    /**
     * Create a new job instance.
     */
    public function __construct() {
        Log::debug(__METHOD__."(".__LINE__."):  new ");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
          InventoryRepositoryInterface $inventoryRepository
        , InventorySummaryRepositoryInterface $inventorySummaryRepository
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->inventorySummaryRepository = $inventorySummaryRepository;

        Log::debug(__METHOD__."(".__LINE__."):  attempts: ".$this->attempts());

        // First count the number of UPCs that have Inventory
        $itemCount = $this->inventoryRepository->countUPCs();
        Log::debug(__METHOD__."(".__LINE__."):  Inventory Items count: ".$itemCount);

        // divide by 2000 to get number of invocations required
        $invocations = intval($itemCount / 2000 * 1.6) + 1;
        Log::debug(__METHOD__."(".__LINE__."):  invocations: ".$invocations);

        $sleepSeconds = 1;
        // run the invocations
        for($i = 0; $i < $invocations; $i++) {
            sleep($sleepSeconds);
            Log::debug(__METHOD__."(".__LINE__."):  run: ".$i);
            //$sleepSeconds = 5;
            $this->inventorySummaryRepository->fireStoredProcedure();
        }
    }

    /**
     * Handle a job failing.
     *
     * @return void
     */
    public function failed() {
        Log::debug(__METHOD__."(".__LINE__.")  failed!");
    }
}
