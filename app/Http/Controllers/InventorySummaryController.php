<?php namespace App\Http\Controllers;

use App\Http\Controllers\SaveRequest;
use App\Jobs\PopulateInventorySummary;
use Illuminate\Support\Facades\View;
use vital40\Repositories\InventorySummaryRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Request;

/**
 * Class InventorySummaryController
 * @package App\Http\Controllers
 */
class InventorySummaryController extends Controller {

    use SaveRequest;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\InventorySummaryRepositoryInterface
	 */ 
	protected $inventorySummaryRepository;
    protected $clean_replenPrty_cd = False;

	/**
	 * Constructor requires Inventory Repository
	 */ 
	public function __construct(
        InventorySummaryRepositoryInterface $inventorySummaryRepository
    ) {
		$this->inventorySummaryRepository = $inventorySummaryRepository;
	}

    protected function filterPreviousRequest(&$previousRequest) {
        unset($previousRequest['_method']);
        unset($previousRequest['_token']);
        if($this->clean_replenPrty_cd) {
            unset($previousRequest['replenPrty_cb_noReplen']);
            unset($previousRequest['replenPrty_cb_20orBelow']);
            unset($previousRequest['replenPrty_cb_40orBelow']);
            unset($previousRequest['replenPrty_cb_60orBelow']);
        } else {
            unset($previousRequest['page']);
        }
        //dd(__METHOD__."(".__LINE__.")", compact('previousRequest'));
    }

    /**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $this->clean_replenPrty_cd = False;
        $invSummary = $this->getRequest('InventorySummary');

        // Are we paging forward, or coming in the first time?
        if (isset($invSummary['page']) == False) {
            // initiate populate InventorySummary
            $this->dispatch(new PopulateInventorySummary());

            // using an implementation of the Inventory Repository Interface
            // $invSummaries = $this->inventorySummaryRepository->paginate($invSummary);
            $invSummaries = [];
        } else {
            // using an implementation of the Inventory Repository Interface
            $invSummaries = $this->inventorySummaryRepository->paginate($invSummary);
        }
        //dd(__METHOD__."(".__LINE__.")", compact('invSummary', 'invSummaries'));

		// Using the view(..) helper function
		return view('pages.invSummary.index', compact('invSummary', 'invSummaries'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        $this->clean_replenPrty_cd = True;
        $invSummary = $this->getRequest('InventorySummary');

		// using an implementation of the Inventory Repository Interface
        $invSummaries = $this->inventorySummaryRepository->paginate($invSummary);
        //$request = Request::all();
        //$checkedList = array_reduce(array_keys($invSummary), function($result, $item) { if(strpos(' '.$item,'replenPrty_cb_') == 1) return $result.', '.$item; else return $result; }, '' );
        //dd(__METHOD__."(".__LINE__.")", compact('invSummary','request','checkedList', 'invSummaries'));

		// populate a View
		return View::make('pages.invSummary.index', compact('invSummary', 'invSummaries'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {

		// using an implementation of the Inventory Repository Interface
        $invSummary = $this->inventorySummaryRepository->find($id);
        //dd($inventory);

		return view('pages.invSummary.show', compact('invSummary'));
	}

}
