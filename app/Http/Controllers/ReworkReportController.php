<?php namespace App\Http\Controllers;

use App\Jobs\JobExperienceInterface;
use App\Reports\ReworkReportInterface;
use Carbon\Carbon;
use vital3\Repositories\CountersRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\JobExperienceRepositoryInterface;
use vital40\Repositories\JobStatusRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use \Auth;
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
class ReworkReportController extends Controller {

    /**
     * Reference an implementation of the Repository Interface
     */
    protected $articleRepository;
    protected $countersRepository;
    protected $jobExperienceRepository;
    protected $jobStatusRepository;
    protected $purchaseOrderDetailRepository;
    protected $receiptHistoryRepository;
    protected $upcRepository;
    protected $jobExperience;
    protected $jobStatusController;
    protected $reworkReport;


    /**
	 * Constructor requires PerformanceTally Repository
	 */ 
	public function __construct(
          CountersRepositoryInterface $countersRepository
        , ArticleRepositoryInterface $articleRepository
        , JobExperienceRepositoryInterface $jobExperienceRepository
        , JobStatusRepositoryInterface $jobStatusRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
	    , ReceiptHistoryRepositoryInterface $receiptHistoryRepository
	    , UPCRepositoryInterface $upcRepository
        , JobExperienceInterface $jobExperience
	    , JobStatusControllerInterface $jobStatusController
        , ReworkReportInterface $reworkReport
    ) {
		$this->countersRepository = $countersRepository;
		$this->articleRepository = $articleRepository;
		$this->jobExperienceRepository = $jobExperienceRepository;
		$this->jobStatusRepository = $jobStatusRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->upcRepository = $upcRepository;
        $this->jobExperience = $jobExperience;
        $this->jobStatusController = $jobStatusController;
        $this->reworkReport = $reworkReport;
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

		$reworkReports = $this->reworkReport->generate($reworkReport['fromDate'], $reworkReport['toDate']);

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

        // Calculate the expected elapsed time (minutes) to run this report
        $itemCount = $this->receiptHistoryRepository->countOn($reworkReport);
        $elapsedTime = $this->jobExperienceRepository->elapsedTime($itemCount, ['name' => Lang::get('internal.jobName.reworkReport')]);
        $expectedCompletion = Carbon::now()->addMinutes($elapsedTime + 1)->format('h:i');
        $reworkReport['itemCount'] = $itemCount;
        $reworkReport['elapsedTime'] = $elapsedTime;
        $reworkReport['expectedCompletion'] = $expectedCompletion;
        $reworkReport['email'] = Auth::user()->email;

        $reworkReports = $this->reworkReport->generate($reworkReport['fromDate'], $reworkReport['toDate']);

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

        $this->jobExperience->setClass(Lang::get('internal.jobName.reworkReport'));
        $reworkReport = Request::all();
        // restore fromDate & toDate
        if(isset($reworkReport['fromDate']) == false and Session::has('fromDate'))
            $reworkReport['fromDate'] = Session::get('fromDate');
        if(isset($reworkReport['toDate'  ]) == false and Session::has('toDate'  ))
            $reworkReport['toDate'  ] = Session::get('toDate'  );
        Log::info('fromDate: '.$reworkReport['fromDate'].',  toDate: '.$reworkReport['toDate'].', exportType: '.$reworkReport['ExportType']);

        // Calculate the expected elapsed time (minutes) to run this report
        $itemCount = $this->receiptHistoryRepository->countOn($reworkReport);
        $this->jobExperience->setNumberOfRecordsProcessed($itemCount);

        if($reworkReport['ExportType'] == 'xls') {
            $reworkReports = $this->reworkReport->generate($reworkReport['fromDate'], $reworkReport['toDate'],0);
            //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports','itemCount'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'ReworkReports-'.$currentDate.$count;

            // create Excel workbook
            $e = Excel::create($fileName, function ($excel) use ($reworkReport, $reworkReports) {

                $excel->sheet('New sheet', function ($sheet) use ($reworkReport, $reworkReports) {

                    $sheet->loadView('pages.reworkReport.excel')
                        ->with('reworkReport',$reworkReport)
                        ->with('reworkReports',$reworkReports);
                });

            });
            Log::debug('Export xls completed');
            $this->jobExperience->ended();
            $e->export('xls');
        }

        elseif($reworkReport['ExportType'] == 'csv') {
            $reworkReports = $this->reworkReport->generate($reworkReport['fromDate'], $reworkReport['toDate'],0);
            //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'ReworkReports-'.$currentDate.$count;

            // create Excel workbook
            $e = Excel::create($fileName, function ($excel) use ($reworkReport, $reworkReports) {

                $excel->sheet('New sheet', function ($sheet) use ($reworkReport, $reworkReports) {

                    $sheet->loadView('pages.reworkReport.excel')
                        ->with('reworkReport',$reworkReport)
                        ->with('reworkReports',$reworkReports);
                });

            });
            Log::debug('Export csv completed');
            $this->jobExperience->ended();
            $e->export('csv');
        } else {

            // We only get here if the $reworkReport['ExportType'] is not xls or csv
            flash()->overlay(Lang::get('internal.errors.export.unsupportedType', ['exportType' => $reworkReport['ExportType']]));
        }

        $reworkReports = $this->reworkReport->generate($reworkReport['fromDate'], $reworkReport['toDate']);

        // save fromDate & toDate
        if(isset($reworkReport['fromDate'])) Session::put('fromDate', $reworkReport['fromDate']);
        if(isset($reworkReport['toDate'  ])) Session::put('toDate'  , $reworkReport['toDate'  ]);

        // Using the view(..) helper function
        //$sessionAll = Session::all();
        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','reworkReports','sessionAll'));
        return view('pages.reworkReport.index', compact('reworkReport', 'reworkReports'));
    }

    /**
     * Find the job and add 'email' to parameters
     */
    public function email() {
        if(Entrust::can('report.rework') == False) return redirect()->route('home');

        $reworkReport = Request::all();
        // restore fromDate & toDate
        if(isset($reworkReport['fromDate']) == false and Session::has('fromDate'))
            $reworkReport['fromDate'] = Session::get('fromDate');
        if(isset($reworkReport['toDate'  ]) == false and Session::has('toDate'  ))
            $reworkReport['toDate'  ] = Session::get('toDate'  );
        if(isset($reworkReport['email'   ]) == false)
            $reworkReport['email'   ] = Auth::user()->email;

        if(isset($reworkReport['btn_Wait']))
            return $this->export();

        // Initiate job ReworkReport
        $parameters = [
            'fromDate' => $reworkReport['fromDate'],
            'toDate' => $reworkReport['toDate'],
            'forUser' => Auth::user()->name,
            'exportType' => $reworkReport['ExportType'],
            'emailTo' => $reworkReport['email'],
        ];
        Log::info('dispatch App\Jobs\ReworkReportJob: ',$parameters);
        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','parameters'));
        $jobID = $this->jobStatusController->dispatchJob(Lang::get('internal.jobName.reworkReport'),$parameters);
        Session::put('jobID', serialize($jobID));
        $reworkReport['status'] = 'submitted';
        //dd(__METHOD__."(".__LINE__.")",compact('reworkReport','sessionAll','parameters','jobID'));

        return redirect()->back()->with('status', trans('internal.jobStatus.submitted', ['id' => $jobID['id']]));
    }

}
