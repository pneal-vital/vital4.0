<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use vital3\Repositories\CountersRepositoryInterface;
use vital40\Repositories\PerformanceTallyRepositoryInterface;
use \Config;
use \Entrust;
use \Excel;
use \Lang;
use \Request;
use \Session;

/**
 * Class ProductivityNumberController
 * @package App\Http\Controllers
 */
class ProductivityNumberController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\PerformanceTallyRepositoryInterface
	 */ 
	protected $countersRepository;
	protected $performanceTallyRepository;


	/**
	 * Constructor requires PerformanceTally Repository
	 */ 
	public function __construct(
          CountersRepositoryInterface $countersRepository
        , PerformanceTallyRepositoryInterface $performanceTallyRepository
    ) {
		$this->countersRepository = $countersRepository;
		$this->performanceTallyRepository = $performanceTallyRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        if(Entrust::hasRole('teamLead') == False) return redirect()->route('home');

        $productivityNumber = Request::all();
        if(count($productivityNumber) == 0) {
            // lets provide a default filter
            $productivityNumber['fromDate'] = Carbon::now()->subHours(24)->format('Y-m-d H:i');
            $productivityNumber['toDate'] = Carbon::now()->format('Y-m-d H:i');
        }

		// using an implementation of the PerformanceTally Repository Interface
		$productivityNumbers = $this->performanceTallyRepository->paginateSum($productivityNumber+['groupBy' => 'dateStamp']);

        // save fromDate & toDate
        if(isset($productivityNumber['fromDate'])) Session::put('fromDate', $productivityNumber['fromDate']);
        if(isset($productivityNumber['toDate'  ])) Session::put('toDate'  , $productivityNumber['toDate'  ]);

		// Using the view(..) helper function
		return view('pages.productivityNumber.index', compact('productivityNumber', 'productivityNumbers'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::hasRole('teamLead') == False) return redirect()->route('home');

        $productivityNumber = Request::all();
        // restore fromDate & toDate
        if(isset($productivityNumber['fromDate']) == false and Session::has('fromDate'))
            $productivityNumber['fromDate'] = Session::get('fromDate');
        if(isset($productivityNumber['toDate'  ]) == false and Session::has('toDate'  ))
            $productivityNumber['toDate'  ] = Session::get('toDate'  );

        // using an implementation of the PerformanceTally Repository Interface
        $productivityNumbers = $this->performanceTallyRepository->paginateSum($productivityNumber+['groupBy' => 'dateStamp']);

        // save fromDate & toDate
        if(isset($productivityNumber['fromDate'])) Session::put('fromDate', $productivityNumber['fromDate']);
        if(isset($productivityNumber['toDate'  ])) Session::put('toDate'  , $productivityNumber['toDate'  ]);

        // Using the view(..) helper function
        return view('pages.productivityNumber.index', compact('productivityNumber', 'productivityNumbers'));
	}

	/**
	 * Export a Filtered Listing of the resource.
     *
     * See: ViewCreators/ExportTypeCreator for a list of the exportTypes we need to support.
	 */
	public function export() {
        if(Entrust::hasRole('teamLead') == False) return redirect()->route('home');

        $productivityNumber = Request::all();
        // restore fromDate & toDate
        if(isset($productivityNumber['fromDate']) == false and Session::has('fromDate'))
            $productivityNumber['fromDate'] = Session::get('fromDate');
        if(isset($productivityNumber['toDate'  ]) == false and Session::has('toDate'  ))
            $productivityNumber['toDate'  ] = Session::get('toDate'  );
        //dd(__METHOD__."(".__LINE__.")",compact('productivityNumber'));

        if($productivityNumber['ExportType'] == 'xls') {
            // using an implementation of the PerformanceTally Repository Interface
            $productivityNumbers = $this->performanceTallyRepository->sumOn($productivityNumber+['groupBy' => 'dateStamp'],0);
            //dd(__METHOD__."(".__LINE__.")",compact('productivityNumber','productivityNumbers'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'ProductivityNumbers-'.$currentDate.$count;

            // create Excel workbook
            Excel::create($fileName, function ($excel) use ($productivityNumber, $productivityNumbers) {

                $excel->sheet('New sheet', function ($sheet) use ($productivityNumber, $productivityNumbers) {

                    $sheet->loadView('pages.productivityNumber.excel')
                        ->with('productivityNumber',$productivityNumber)
                        ->with('productivityNumbers',$productivityNumbers);
                });

            })->export('xls');
        }

        if($productivityNumber['ExportType'] == 'csv') {
            // using an implementation of the PerformanceTally Repository Interface
            $productivityNumbers = $this->performanceTallyRepository->sumOn($productivityNumber+['groupBy' => 'dateStamp'],0);
            //dd(__METHOD__."(".__LINE__.")",compact('productivityNumber','productivityNumbers'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'ProductivityNumbers-'.$currentDate.$count;

            // create Excel workbook
            Excel::create($fileName, function ($excel) use ($productivityNumber, $productivityNumbers) {

                $excel->sheet('New sheet', function ($sheet) use ($productivityNumber, $productivityNumbers) {

                    $sheet->loadView('pages.productivityNumber.excel')
                        ->with('productivityNumber',$productivityNumber)
                        ->with('productivityNumbers',$productivityNumbers);
                });

            })->export('csv');
        }

        // using an implementation of the PerformanceTally Repository Interface
        $productivityNumbers = $this->performanceTallyRepository->paginateSum($productivityNumber+['groupBy' => 'dateStamp']);

        // save fromDate & toDate
        if(isset($productivityNumber['fromDate'])) Session::put('fromDate', $productivityNumber['fromDate']);
        if(isset($productivityNumber['toDate'  ])) Session::put('toDate'  , $productivityNumber['toDate'  ]);

        // Using the view(..) helper function
        return view('pages.productivityNumber.index', compact('productivityNumber', 'productivityNumbers'));
	}

}
