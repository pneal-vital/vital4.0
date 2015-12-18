<?php namespace App\Http\Controllers\Receive;

// use a Repository Interface
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\View;
use vital3\Repositories\CountersRepositoryInterface;
use vital3\Repositories\InventoryRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\PurchaseOrderRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use \Auth;
use \Config;
use \Entrust;
use \Excel;
use \Flash;
use \Lang;
use \Log;
use \POReconciliation;
use \Request;
use \Route;
use \Session;

/**
 * Class POReconciliationController
 * @package App\Http\Controllers
 */
class POReconciliationController extends Controller {

    /**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\PurchaseOrderRepositoryInterface
	 */
    protected $articleRepository;
    protected $countersRepository;
    protected $inventoryRepository;
    protected $purchaseOrderDetailRepository;
    protected $purchaseOrderRepository;
    protected $receiptHistoryRepository;
    protected $upcRepository;
    protected $userActivityRepository;

	/**
	 * Constructor requires purchaseOrder Repository
	 */
	public function __construct(
          ArticleRepositoryInterface $articleRepository
        , CountersRepositoryInterface $countersRepository
        , InventoryRepositoryInterface $inventoryRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , PurchaseOrderRepositoryInterface $purchaseOrderRepository
		, ReceiptHistoryRepositoryInterface $receiptHistoryRepository
		, UPCRepositoryInterface $upcRepository
		, UserActivityRepositoryInterface $userActivityRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->countersRepository = $countersRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->upcRepository = $upcRepository;
        $this->userActivityRepository = $userActivityRepository;
    }

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
		//dd(Auth::user());

		// lets provide a default filter
		$purchaseOrder = [
			'Expected' => Carbon::today()->format('Y-m'),
			'Status'   => Config::get('constants.purchaseOrder.status.receiving'),
		];
		// using an implementation of the purchaseOrder Repository Interface
		$purchaseOrders = $this->purchaseOrderRepository->paginate($purchaseOrder);

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status'), 'OPEN' => Lang::get('lists.purchaseOrder.status.OPEN'), 'REC' => Lang::get('lists.purchaseOrder.status.REC')];

		// Using the view(..) helper function
		return view('pages.poReconciliation.index', compact('purchaseOrder', 'purchaseOrders', 'statuses'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$purchaseOrder = Request::all();

		// using an implementation of the purchaseOrder Repository Interface
		$purchaseOrders = $this->purchaseOrderRepository->paginate($purchaseOrder);
        //dd(compact('purchaseOrder', 'purchaseOrders'));

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status'), 'OPEN' => Lang::get('lists.purchaseOrder.status.OPEN'), 'REC' => Lang::get('lists.purchaseOrder.status.REC')];

		// populate a View
        return View::make('pages.poReconciliation.index', compact('purchaseOrder', 'purchaseOrders', 'statuses'));
    }

    /**
     * display the specific resource
     */
    public function show($id) {
        Log::debug(__METHOD__.'('.__LINE__.'):  show('.$id.')');

        $completedArticles = 'Hide_Completed_Articles';
        if(Session::has('completedArticles')) {
            $completedArticles = Session::get('completedArticles');
        }
        $completedArticles = $completedArticles == 'Hide_Completed_Articles' ? 'Show_Completed_Articles' : 'Hide_Completed_Articles';

        return $this->doShow($id, $completedArticles);
    }

    /**
     * display the specific resource
     */
    public function review($id) {
        Log::debug(__METHOD__.'('.__LINE__.'):  review('.$id.')');
        $request = (object) Request::all();

        // Button btn_Hide_Completed_Articles
        if(isset($request->btn_Show_only_variances)) {
            return $this->doShow($id, 'Show_Completed_Articles');
        }

        // Button btn_Show_Completed_Articles
        if(isset($request->btn_Show_all_details)) {
            return $this->doShow($id, 'Hide_Completed_Articles');
        }

        // Button btn_Confirm
        if(isset($request->btn_Confirm)) {
            Flash::overlay('Are you sure you want to confirm this Purchase Order?', 'Confirm Purchase Order?');
        }

        //$level = Session::get('flash_notification.level');
        //dd($level);

        return $this->doShow($id);
    }

    /**
	 * display the specific resource
	 */
	public function doShow($id, $completedArticles='Hide_Completed_Articles') {
        Session::put('completedArticles', $completedArticles);
        $page = 1;
        if(Request::has('page'))
            $page = Request::get('page');
        //dd(__METHOD__.'('.__LINE__.')',compact('id','completedArticles','page'));

        // using an implementation of the purchaseOrder Repository Interface
		$purchaseOrder = $this->purchaseOrderRepository->find($id);

        // calling into business logic
        POReconciliation::selectPO($purchaseOrder);

		/* Merge a list Purchase Order Details with their Articles
		 *
         * First we build an array of this POD Articles
		 * select the 10 lines that will appear on this page
		 * fill out the calculated received / expected values
		 * and lastly hand it to our paginator
		 */
        $podArticles = $this->articleRepository->getPODArticles($purchaseOrder->objectID,0);
        $thisPage = array_slice($podArticles, ($page - 1) * 10, 10);
        $this->calculateReceivedExpected($thisPage);

        //dd(__METHOD__.'('.__LINE__.')',compact('id','completedArticles','podArticles','thisPage','recArticles'));
        $receiveArticles = new Paginator($thisPage, count($podArticles), 10, $page, [
            'path' => route('poReconciliation.show', ['id' => $id]),
        ]);

		return view('pages.poReconciliation.show', compact('purchaseOrder', 'completedArticles', 'receiveArticles'));
	}

    /**
	 * Calculate the Expected and Received values
	 */
	public function calculateReceivedExpected($podArticles) {
        foreach($podArticles as $podArticle) {
            Log::debug(__METHOD__.'('.__LINE__.'):  podID: '.$podArticle->purchaseOrderDetailID.', articleID: '.$podArticle->articleID);

            // $received is calculated from ReceiptHistory counting Received UPC into Tote... entries
            $filter = [
                'POD'      => $podArticle->purchaseOrderDetailID,
                'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote', ['time' => '%', 'upcSKU' => '%', 'n' => '%', 'ofn' => '%'])),
            ];

            // Status: 0 => '', 1 => 'success', 2 => 'warning'
            $status=0; $expected = 0; $received=0;
            $upcs = $this->upcRepository->getArticleUPCs($podArticle->articleID,0);
            //Log::debug(__METHOD__.'('.__LINE__.'):  upcs: '.count($upcs));
            foreach($upcs as $upc) {
                # accumulate expected and received
                $expected += $upcExpected = $podArticle->Expected_Qty * $upc->parents[$podArticle->articleID]->Quantity;
                $filter['UPC'] = $upc->objectID;
                $received += $upcReceived = $this->receiptHistoryRepository->countOn($filter);
                //Log::debug(__METHOD__.'('.__LINE__.'):  received: '.$received);
                # Status calculations
                if($upcExpected > $upcReceived) {
                    $upcStatus=0;
                } elseif($upcExpected == $upcReceived) {
                    $upcStatus=1;
                } else {
                    $upcStatus=2;
                }
                $status = $upcStatus > $status ? $upcStatus : $status;
            }
            $statuses=['', 'success', 'warning'];
            //dd(__METHOD__.'('.__LINE__.')',compact('podArticles','upcs','expected','upcExpected','received','upcReceived','status'));
            //Log::debug(__METHOD__.'('.__LINE__.'):  status: '.$status);
            // build results
            $podArticle->status       = $statuses[$status];
            $podArticle->Received_Qty = '' . $received . '/' . $expected;
            if(isset($podArticle->rework) == false)
                $podArticle->rework   = Lang::get('labels.rework_unknown');

        }
	}

    /**
	 * Merge a list Purchase Order Details and their Articles
	 */
	public function mergePODetailWithArticle($id, $completedArticles) {
		$results = [];
		// filter to get purchaseOrderDetails
		$filter = [
			'Order_Number' => $id,
		];
		// get the PurchaseOrderDetails
		$purchaseOrderDetails = $this->purchaseOrderDetailRepository->filterOn($filter, 0);
        Log::debug(__METHOD__.'('.__LINE__.'):  PODs: '.(isset($purchaseOrderDetails) ? count($purchaseOrderDetails) : 'none found'));
		//dd($purchaseOrderDetails);
		if(isset($purchaseOrderDetails) && count($purchaseOrderDetails)) {

			// foreach purchaseOrderDetail record, merge with Article
            foreach($purchaseOrderDetails as $purchaseOrderDetail) {
				$article = $this->articleRepository->find($purchaseOrderDetail->SKU);
                Log::debug(__METHOD__.'('.__LINE__.'):  articleID: '.$article->objectID);

                // $received is calculated from ReceiptHistory counting Received UPC into Tote... entries
                $filter = [
                    'POD'      => $purchaseOrderDetail->objectID,
                    'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote', ['time' => '%', 'upcSKU' => '%', 'n' => '%', 'ofn' => '%'])),
                ];

                // Status: 0 => '', 1 => 'success', 2 => 'warning'
                $status=0; $expected = 0; $received=0;
                $upcs = $this->upcRepository->getArticleUPCs($article->objectID,0);
                Log::debug(__METHOD__.'('.__LINE__.'):  upcs: '.count($upcs));
                foreach($upcs as $upc) {
                    # accumulate expected and received
                    $expected += $upcExpected = $purchaseOrderDetail->Expected_Qty * $upc->parents[$article->objectID]->Quantity;
                    $filter['UPC'] = $upc->objectID;
                    $received += $upcReceived = $this->receiptHistoryRepository->countOn($filter);
                    Log::debug(__METHOD__.'('.__LINE__.'):  received: '.$received);
                    # Status calculations
                    if($upcExpected > $upcReceived) {
                        $upcStatus=0;
                    } elseif($upcExpected == $upcReceived) {
                        $upcStatus=1;
                    } else {
                        $upcStatus=2;
                    }
                    $status = $upcStatus > $status ? $upcStatus : $status;
                }
                $statuses=['', 'success', 'warning'];
                Log::debug(__METHOD__.'('.__LINE__.'):  status: '.$status);

                if($status == 1 and $expected == $received and $completedArticles == 'Show_Completed_Articles') {
                    // hide the all success Articles, when the button will say Show..
                } else {
					// build results
					$result = [
                        'status'                => $statuses[$status],
						'purchaseOrderDetailID' => $purchaseOrderDetail->objectID,
						'Expected_Qty'          => $purchaseOrderDetail->Expected_Qty,
						'Received_Qty'          => '' . $received . '/' . $expected,
						'articleID'             => $article->objectID,
						'Client_SKU'            => $article->Client_SKU,
						'Description'           => $article->Description,
						'UOM'                   => $article->UOM,
						'Case_Pack'             => $article->Case_Pack,
						'Colour'                => $article->Colour,
						'Zone'                  => $article->Zone,
						'rework'                => isset($article->rework) ? $article->rework : Lang::get('labels.rework_unknown'),
					];
					$results[$article->Client_SKU] = $result;
				}
			}

		}
		ksort($results);
		//dd($results);
		return $results;
	}

    /**
     * Confirm the Purchase Order
     */
    public function confirm($id) {
        Log::debug(__METHOD__.'('.__LINE__.'):  confirm('.$id.')');
        $request = (object) Request::all();
        //dd(__METHOD__.'('.__LINE__.')',compact('request'));

        // Button btn_No
        if(isset($request->btn_No)) {
            return $this->show($id);
        }

        // Button btn_Confirm
        if(isset($request->btn_Confirm)) {

            // calling into business logic
            POReconciliation::confirm($id);

            Flash::overlay('Purchase Order reconciliation completed successfully', 'Purchase Order Confirmed');
        }

        //$level = Session::get('flash_notification.level');
        //dd(__METHOD__.'('.__LINE__.')',compact('request','level'));

        return redirect()->route('poReconciliation.index');
    }

    /**
     * Export a Filtered Listing of the resource.
     *
     * See: ViewCreators/ExportTypeCreator for a list of the exportTypes we need to support.
     */
    public function export($id) {
        if(Entrust::hasRole('teamLead') == False) return redirect()->route('home');
        $request = (object) Request::all();
        $filter = ['id' => $id];
        //dd(__METHOD__.'('.__LINE__.')',compact('id','request','filter'));

        if($request->ExportType == 'xls') {

            /* Merge a list Purchase Order Details with their Articles
             *
             * First we build an array of this POD Articles
             * fill out the calculated received / expected values
             * and lastly hand it to our xls formatter
             */
            $podArticles = $this->articleRepository->getPODArticles($id,0);
            $this->calculateReceivedExpected($podArticles);
            //dd(__METHOD__."(".__LINE__.")",compact('id','request','filter','podArticles'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'POReconciliation-'.$currentDate.$count;
            //dd(__METHOD__."(".__LINE__.")",compact('id','request','filter','podArticles','fileName'));

            // create Excel workbook
            Excel::create($fileName, function ($excel) use ($filter, $podArticles) {

                $excel->sheet('New sheet', function ($sheet) use ($filter, $podArticles) {

                    $sheet->loadView('pages.poReconciliation.excel')
                        ->with('filter',$filter)
                        ->with('podArticles',$podArticles);
                });

            })->export('xls');
        }

        if($request->ExportType == 'csv') {

            /* Merge a list Purchase Order Details with their Articles
             *
             * First we build an array of this POD Articles
             * fill out the calculated received / expected values
             * and lastly hand it to our xls formatter
             */
            $podArticles = $this->articleRepository->getPODArticles($id,0);
            $this->calculateReceivedExpected($podArticles);
            //dd(__METHOD__."(".__LINE__.")",compact('id','request','filter','podArticles'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'POReconciliation-'.$currentDate.$count;
            //dd(__METHOD__."(".__LINE__.")",compact('id','request','filter','podArticles','fileName'));

            // create Excel workbook
            Excel::create($fileName, function ($excel) use ($filter, $podArticles) {

                $excel->sheet('New sheet', function ($sheet) use ($filter, $podArticles) {

                    $sheet->loadView('pages.poReconciliation.excel')
                        ->with('filter',$filter)
                        ->with('podArticles',$podArticles);
                });

            })->export('csv');
        }

    }

}
