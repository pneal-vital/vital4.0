<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use vital3\Repositories\CountersRepositoryInterface;
use vital40\Repositories\PerformanceTallyRepositoryInterface;
use \Auth;
use \Config;
use \Entrust;
use \Excel;
use \Lang;
use \Request;
use \Session;

/**
 * Class AssociateNumberController
 * @package App\Http\Controllers
 */
class AssociateNumberController extends Controller {

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
        CountersRepositoryInterface $countersRepository,
        PerformanceTallyRepositoryInterface $performanceTallyRepository
    ) {
		$this->countersRepository = $countersRepository;
		$this->performanceTallyRepository = $performanceTallyRepository;
	}


	/**
	 * Display a Listing of the resource.
     *
     * http://localhost:8888/productivityNumber?_method=PATCH&_token=NF7ewZhJ8Km8eY3JokeIvwn5uZtDDigF5QV2LmeZ&fromDate=2015-08-05%2000:00&toDate=2015-08-14%2000&page=1
	 */
	public function index() {
        if(Entrust::hasRole('teamLead') == False) return redirect()->route('home');

        $associateNumber = Request::all();
        //dd(__METHOD__."(".__LINE__.")",compact('associateNumber'));

        if(count($associateNumber) == 0) {
            // lets provide a default filter
            $associateNumber['fromDate'] = Carbon::now()->subHours(24)->format('Y-m-d H:i');
            $associateNumber['toDate'] = Carbon::now()->format('Y-m-d H:i');
        }

		// using an implementation of the PerformanceTally Repository Interface
		$associateNumbers = $this->performanceTallyRepository->paginateSum($associateNumber+['groupBy' => 'userName']);

        // save fromDate & toDate
        if(isset($associateNumber['fromDate'])) Session::put('fromDate', $associateNumber['fromDate']);
        if(isset($associateNumber['toDate'  ])) Session::put('toDate'  , $associateNumber['toDate'  ]);

		// Using the view(..) helper function
		return view('pages.associateNumber.index', compact('associateNumber', 'associateNumbers'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::hasRole('teamLead') == False) return redirect()->route('home');

        $associateNumber = Request::all();
        // restore fromDate & toDate
        if(isset($associateNumber['fromDate']) == false and Session::has('fromDate'))
            $associateNumber['fromDate'] = Session::get('fromDate');
        if(isset($associateNumber['toDate'  ]) == false and Session::has('toDate'  ))
            $associateNumber['toDate'  ] = Session::get('toDate'  );

        // using an implementation of the PerformanceTally Repository Interface
        $associateNumbers = $this->performanceTallyRepository->paginateSum($associateNumber+['groupBy' => 'userName']);

        // save fromDate & toDate
        if(isset($associateNumber['fromDate'])) Session::put('fromDate', $associateNumber['fromDate']);
        if(isset($associateNumber['toDate'  ])) Session::put('toDate'  , $associateNumber['toDate'  ]);

        // Using the view(..) helper function
        return view('pages.associateNumber.index', compact('associateNumber', 'associateNumbers'));
	}

	/**
	 * Export a Filtered Listing of the resource.
     *
     * See: ViewCreators/ExportTypeCreator for a list of the exportTypes we need to support.
	 */
	public function export() {
        if(Entrust::hasRole('teamLead') == False) return redirect()->route('home');

        $associateNumber = Request::all();
        // restore fromDate & toDate
        if(isset($associateNumber['fromDate']) == false and Session::has('fromDate'))
            $associateNumber['fromDate'] = Session::get('fromDate');
        if(isset($associateNumber['toDate'  ]) == false and Session::has('toDate'  ))
            $associateNumber['toDate'  ] = Session::get('toDate'  );
        //dd(__METHOD__."(".__LINE__.")",compact('associateNumber'));

        if($associateNumber['ExportType'] == 'xls') {
            // using an implementation of the PerformanceTally Repository Interface
            $associateNumbers = $this->performanceTallyRepository->sumOn($associateNumber+['groupBy' => 'userName'],0);
            //dd(__METHOD__."(".__LINE__.")",compact('associateNumber','associateNumbers'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'AssociateNumbers-'.$currentDate.$count;

            // create Excel workbook
            Excel::create($fileName, function ($excel) use ($associateNumber, $associateNumbers) {

                $excel->sheet('New sheet', function ($sheet) use ($associateNumber, $associateNumbers) {

                    $sheet->loadView('pages.associateNumber.excel')
                        ->with('associateNumber',$associateNumber)
                        ->with('associateNumbers',$associateNumbers);
                });

            })->export('xls');
        }

        if($associateNumber['ExportType'] == 'csv') {
            // using an implementation of the PerformanceTally Repository Interface
            $associateNumbers = $this->performanceTallyRepository->sumOn($associateNumber+['groupBy' => 'userName'],0);
            //dd(__METHOD__."(".__LINE__.")",compact('associateNumber','associateNumbers'));

            // TODO file name should be calculated in a separate class
            $count = sprintf('%04d', $this->countersRepository->increment('exportFile'));
            $currentDate = Carbon::now()->format('YmdHis');
            $fileName = 'AssociateNumbers-'.$currentDate.$count;

            // create Excel workbook
            Excel::create($fileName, function ($excel) use ($associateNumber, $associateNumbers) {

                $excel->sheet('New sheet', function ($sheet) use ($associateNumber, $associateNumbers) {

                    $sheet->loadView('pages.associateNumber.excel')
                        ->with('associateNumber',$associateNumber)
                        ->with('associateNumbers',$associateNumbers);
                });

            })->export('csv');
        }

        // using an implementation of the PerformanceTally Repository Interface
        $associateNumbers = $this->performanceTallyRepository->paginateSum($associateNumber+['groupBy' => 'userName']);

        // save fromDate & toDate
        if(isset($associateNumber['fromDate'])) Session::put('fromDate', $associateNumber['fromDate']);
        if(isset($associateNumber['toDate'  ])) Session::put('toDate'  , $associateNumber['toDate'  ]);

        // Using the view(..) helper function
        return view('pages.associateNumber.index', compact('associateNumber', 'associateNumbers'));
	}

    /**
     * Display a specific resource
     */
    public function show($id)
    {
        if(Entrust::hasRole('teamLead') == False) {
            $id = Auth::user()->name;
            //dd(__METHOD__ . "(" . __LINE__ . ")", compact('id'));
        }
        $assNum = Request::all();
        // restore fromDate & toDate
        if (isset($assNum['fromDate']) == false and Session::has('fromDate'))
            $assNum['fromDate'] = Session::get('fromDate');
        if (isset($assNum['toDate']) == false and Session::has('toDate'))
            $assNum['toDate'] = Session::get('toDate');
        $assNum['userName'] = $id;
        //dd(__METHOD__ . "(" . __LINE__ . ")", compact('id', 'assnum'));

        // using an implementation of the PerformanceTally Repository Interface
        $associateNumber = $this->performanceTallyRepository->sumOn($assNum,1);

        if(isset($assNum['fromDate']) and isset($associateNumber->fromDate) == false)  $associateNumber->fromDate = $assNum['fromDate'];
        if(isset($assNum['toDate'  ]) and isset($associateNumber->toDate  ) == false)  $associateNumber->toDate   = $assNum['toDate'  ];
        if(isset($assNum['userName']) and isset($associateNumber->userName) == false)  $associateNumber->userName = $assNum['userName'];

        //dd(__METHOD__."(".__LINE__.")",compact('id','assnum','associateNumber'));
        return view('pages.associateNumber.show', compact('associateNumber'));
    }

}
