<?php namespace App\Http\Controllers;

use App\Jobs\ReworkReportInterface;
use Carbon\Carbon;
use vital3\Repositories\CountersRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\JobExperienceRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use \Auth;
use \Config;
use \Entrust;
use \Excel;
use \Lang;
use \Log;
use \Request;
use \Session;

/**
 * Class ReworkReportController
 * @package App\Http\Controllers
 */
class ReworkReportController extends Controller implements ReworkReportControllerInterface {

    /**
     * Reference an implementation of the Repository Interface
     */
    protected $articleRepository;
    protected $countersRepository;
    protected $jobExperienceRepository;
    protected $purchaseOrderDetailRepository;
    protected $receiptHistoryRepository;
    protected $upcRepository;
    protected $jobStatusController;


    /**
	 * Constructor requires PerformanceTally Repository
	 */ 
	public function __construct(
          CountersRepositoryInterface $countersRepository
        , ArticleRepositoryInterface $articleRepository
        , JobExperienceRepositoryInterface $jobExperienceRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
	    , ReceiptHistoryRepositoryInterface $receiptHistoryRepository
	    , UPCRepositoryInterface $upcRepository
	    , JobStatusControllerInterface $jobStatusController
    ) {
		$this->countersRepository = $countersRepository;
		$this->articleRepository = $articleRepository;
		$this->jobExperienceRepository = $jobExperienceRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->upcRepository = $upcRepository;
        $this->jobStatusController = $jobStatusController;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        if(Entrust::can('report.rework') == False) return redirect()->route('home');

        $reworkReport = Request::all();
        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport'));

        if(count($reworkReport) == 0) {
            // lets provide a default filter
            $reworkReport['fromDate'] = Carbon::now()->subMonths(1)->format('Y-m-d H:i');
            $reworkReport['toDate'] = Carbon::now()->format('Y-m-d H:i');
        }

		$reworkReports = $this->CalculateReport($reworkReport['fromDate'], $reworkReport['toDate']);

        // save fromDate & toDate
        if(isset($reworkReport['fromDate'])) Session::put('fromDate', $reworkReport['fromDate']);
        if(isset($reworkReport['toDate'  ])) Session::put('toDate'  , $reworkReport['toDate'  ]);

        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports','jobID'));
        // Using the view(..) helper function
		return view('pages.reworkReport.index', compact('reworkReport', 'reworkReports'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::can('report.rework') == False) return redirect()->route('home');

        $reworkReport = Request::all();
        // restore fromDate & toDate
        if(isset($reworkReport['fromDate']) == false and Session::has('fromDate'))
            $reworkReport['fromDate'] = Session::get('fromDate');
        if(isset($reworkReport['toDate'  ]) == false and Session::has('toDate'  ))
            $reworkReport['toDate'  ] = Session::get('toDate'  );
        if(isset($reworkReport['email'   ]) == false)
            $reworkReport['email'   ] = Auth::user()->email;

        $reworkReports = $this->CalculateReport($reworkReport['fromDate'], $reworkReport['toDate']);

        // save fromDate & toDate
        if(isset($reworkReport['fromDate'])) Session::put('fromDate', $reworkReport['fromDate']);
        if(isset($reworkReport['toDate'  ])) Session::put('toDate'  , $reworkReport['toDate'  ]);

        //$sessionAll = Session::all();
        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports','sessionAll'));
        // Using the view(..) helper function
        return view('pages.reworkReport.index', compact('reworkReport', 'reworkReports'));
	}

	/**
	 * Export a Filtered Listing of the resource.
     *
     * See: ViewCreators/ExportTypeCreator for a list of the exportTypes we need to support.
	 */
	public function export() {
        if(Entrust::can('report.rework') == False) return redirect()->route('home');

        $reworkReport = Request::all();
        // restore fromDate & toDate
        if(isset($reworkReport['fromDate']) == false and Session::has('fromDate'))
            $reworkReport['fromDate'] = Session::get('fromDate');
        if(isset($reworkReport['toDate'  ]) == false and Session::has('toDate'  ))
            $reworkReport['toDate'  ] = Session::get('toDate'  );

        /*
         * We have been asked to export the report
         *
         * 1. Initiate job ReworkReport
         * 2. Calculate the expected elapsed time (minutes) to run this report
         * 1f more then 2 minutes, ask if they want the results emailed to them, provide email id: ___
         * 3.1. yes -> return ReworkReport job created/started/expect results in about n minutes
         * 3.2. no -> check if ReworkReport job started, wait n minutes, check again ???
         *            if not started, run report from here and export the results
         */

        // 1. Initiate job ReworkReport
        $jobParams = ['fromDate' => $reworkReport['fromDate'], 'toDate' => $reworkReport['toDate'], 'forUser' => Auth::user()->name];
        if(isset($reworkReport['toDate']) && $reworkReport['toDate'] != 0)
            $jobParams['exportType'] = $reworkReport['ExportType'];
        $jobID = $this->jobStatusController->dispatchJob('App\Jobs\ReworkReport',$jobParams);
        Session::put('jobID', serialize($jobID));
        $reworkReport['status'] = 'submitted';
        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','sessionAll','jobParams','jobID'));

        // 2. Calculate the expected elapsed time (minutes) to run this report
        $itemCount = $this->receiptHistoryRepository->countOn($reworkReport);
        $elapsedTime = $this->jobExperienceRepository->elapsedTime($itemCount, ['name' => 'App\\Jobs\\ReworkReport']);
        if($elapsedTime > 2) {
            $expectedCompletion = Carbon::now()->addMinutes($elapsedTime);
            $reworkReport['itemCount'] = $itemCount;
            $reworkReport['elapsedTime'] = $elapsedTime;
            $reworkReport['expectedCompletion'] = $expectedCompletion;
            $reworkReport['email'] = Auth::user()->email;
            //$sessionAll = Session::all();
            //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','jobParams','jobID','itemCount','elapsedTime','expectedCompletion','sessionAll'));
        }

        elseif($reworkReport['ExportType'] == 'xls') {
            $reworkReports = $this->CalculateReport($reworkReport['fromDate'], $reworkReport['toDate'],0);
            //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'ReworkReports-'.$currentDate.$count;

            // create Excel workbook
            Excel::create($fileName, function ($excel) use ($reworkReport, $reworkReports) {

                $excel->sheet('New sheet', function ($sheet) use ($reworkReport, $reworkReports) {

                    $sheet->loadView('pages.reworkReport.excel')
                        ->with('reworkReport',$reworkReport)
                        ->with('reworkReports',$reworkReports);
                });

            })->export('xls');
        }

        elseif($reworkReport['ExportType'] == 'csv') {
            $reworkReports = $this->CalculateReport($reworkReport['fromDate'], $reworkReport['toDate'],0);
            //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'ReworkReports-'.$currentDate.$count;

            // create Excel workbook
            Excel::create($fileName, function ($excel) use ($reworkReport, $reworkReports) {

                $excel->sheet('New sheet', function ($sheet) use ($reworkReport, $reworkReports) {

                    $sheet->loadView('pages.reworkReport.excel')
                        ->with('reworkReport',$reworkReport)
                        ->with('reworkReports',$reworkReports);
                });

            })->export('csv');
        }

        $reworkReports = $this->CalculateReport($reworkReport['fromDate'], $reworkReport['toDate']);

        // save fromDate & toDate
        if(isset($reworkReport['fromDate'])) Session::put('fromDate', $reworkReport['fromDate']);
        if(isset($reworkReport['toDate'  ])) Session::put('toDate'  , $reworkReport['toDate'  ]);

        // Using the view(..) helper function
        //$sessionAll = Session::all();
        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports','sessionAll'));
        return view('pages.reworkReport.index', compact('reworkReport', 'reworkReports'));
    }


    /**
     * Display a Listing of the resource.
     */
    public function CalculateReport($fromDate, $toDate, $limit = 10) {
        Log::debug('fromDate: '.$fromDate.',  toDate: '.$toDate.', limit: '.$limit);
        $pods = [];
        $results = [];

        // filter to count received
        $filter = [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote')),
        ];
        $receiptHistories = $this->receiptHistoryRepository->filterOn($filter, $limit);

        //dd(__METHOD__."(".__LINE__.")",compact('fromDate','toDate','limit','receiptHistories','pods','results'));
        Log::debug('count(receiptHistories): '.count($receiptHistories));

        foreach($receiptHistories as $receiptHistory) {
            // can we use this record?
            if(isset($receiptHistory['PO']) == false
            || isset($receiptHistory['POD']) == false
            || isset($receiptHistory['Article']) == false
            || isset($receiptHistory['UPC']) == false) {
                continue;
            }
            $podID = $receiptHistory['POD'];
            $articleID = $receiptHistory['Article'];
            $upcID = $receiptHistory['UPC'];

            // if we don't already know, calculate Expected_Qty (case level) and expected (UPC level)
            if(isset($pods[$podID]) == false) {
                $pods[$podID] = [];
                $pod = $this->purchaseOrderDetailRepository->find($podID);
                Log::debug('podID: '.$podID.',  pod->Expected_Qty: '.$pod->Expected_Qty);
                $pods[$podID]['Expected_Qty'] = $pod->Expected_Qty;
                $pods[$podID]['Status'] = $pod->Status;
            }
            if(isset($pods[$articleID]) == false) {
                $pods[$articleID] = [];
                $article = $this->articleRepository->find($articleID);
                Log::debug('articleID: '.$articleID.',  article->rework: '.$article->rework);
                $pods[$articleID]['rework'] = $article->rework;
            }
            if(isset($pods[$podID][$upcID]) == false) {
                $pods[$podID][$upcID] = [];
                $article = $this->articleRepository->find($articleID);
                $pods[$articleID]['rework'] = $article->rework;
                $upc = $this->upcRepository->find($upcID);
                $pods[$podID][$upcID]['Client_SKU'] = $upc->Client_SKU;
                $expected = 0;
                if(isset($upc->parents[$articleID]) && isset($upc->parents[$articleID]->Quantity)) {
                    Log::debug('upcID: '.$upcID.',  upc->parents['.$articleID.']->Quantity: '.$upc->parents[$articleID]->Quantity);
                    $expected = $upc->parents[$articleID]->Quantity * $pods[$podID]['Expected_Qty'];
                }
                $pods[$podID][$upcID]['expected'] = $expected;
            }
            //dd(__METHOD__."(".__LINE__.")",compact('fromDate','toDate','limit','receiptHistories','receiptHistory','podID','articleID','upcID','pods','results'));

            // at this point we should have all the data we want
            $key = $receiptHistory['PO'].",$podID,$upcID,".$receiptHistory['User_Name'];
            if(isset($results[$key])) {
                $result = $results[$key];
                $result->Actual_Qty++;
                $varVal = abs($result->Actual_Qty - $result->Expected_Qty);
                $varStr = ($result->Expected_Qty == $result->Actual_Qty ? '' : ($result->Expected_Qty < $result->Actual_Qty ? 'over' : 'short'));
                $result->Variance = "$varVal $varStr";
                if($result->fromDate > $receiptHistory['created_at']) $result->fromDate = $receiptHistory['created_at'];
                if($result->toDate   < $receiptHistory['created_at']) $result->toDate   = $receiptHistory['created_at'];
                $results[$key] = $result;
            } else {
                $expected = $pods[$podID][$upcID]['expected'];
                $received = 1;
                $varVal = abs($received - $expected);
                $varStr = ($expected == $received ? '' : ($expected < $received ? 'over' : 'short'));
                $results[$key] = (object)[
                      'Purchase_Order' => $receiptHistory['PO']
                    , 'PO_Class'       => 'Vendor'
                    , 'Client_SKU'     => $pods[$podID][$upcID]['Client_SKU']
                    , 'upcID'          => $upcID
                    , 'Expected_Qty'   => $expected
                    , 'Actual_Qty'     => $received
                    , 'Variance'       => "$varVal $varStr"
                    , 'fromDate'       => $receiptHistory['created_at']
                    , 'toDate'         => $receiptHistory['created_at']
                    , 'User_Name'      => $receiptHistory['User_Name']
                    , 'Status'         => $pods[$podID]['Status']
                    , 'rework'         => $pods[$articleID]['rework']
                ];
            }
        }
        ksort($results);
        //dd(__METHOD__."(".__LINE__.")",compact('fromDate','toDate','limit','receiptHistories','pods','results'));
        Log::debug('count(results): '.count($results));

        return array_values($results);
    }

}
