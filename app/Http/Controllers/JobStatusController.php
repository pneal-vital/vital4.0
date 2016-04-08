<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use vital40\Repositories\JobStatusRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Request;

/**
 * Class JobStatusController
 * @package App\Http\Controllers
 */
class JobStatusController extends Controller implements JobStatusControllerInterface {

	/**
	 * Reference an implementation of the Repository Interface
	 */
	protected $jobStatusRepository;


	/**
	 * Constructor requires JobStatus Repository
	 */ 
	public function __construct(JobStatusRepositoryInterface $jobStatusRepository) {
		$this->jobStatusRepository = $jobStatusRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $jobStatus = Request::all();
        if(count($jobStatus) == 0) {
            // lets provide a default filter
            $jobStatus['Status'] = Config::get('constants.jobStatus.status.open');
        }

		// using an implementation of the JobStatus Repository Interface
		$jobStatuses = $this->jobStatusRepository->paginate($jobStatus);

		// Using the view(..) helper function
		return view('pages.jobStatus.index', compact('jobStatus', 'jobStatuses'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$jobStatus = Request::all();

		// using an implementation of the JobStatus Repository Interface
		$jobStatuses = $this->jobStatusRepository->paginate($jobStatus);

		// populate a View
		return View::make('pages.jobStatus.index', compact('jobStatus', 'jobStatuses'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {

		// using an implementation of the JobStatus Repository Interface
		$jobStatus = $this->jobStatusRepository->find($id);
		//dd($jobStatus);

        $levels = $this->buildHeading($jobStatus);
        //dd($levels);

        // get children Pallets of this JobStatus
        $filter = [
            'container.parent' => $id,
        ];
        //dd($pallets);

		return view('pages.jobStatus.show', compact('jobStatus', 'levels'));
	}

    /**
     * Dispatch a new job, to start processing immediately.
     *
     * To achieve; dispatch(new ReworkReportJob($fromDate, $toDate, 'csv'));
     * invoke this method dispatchJob('App\Jobs\ReworkReportJob',[$fromDate, $toDate, 'csv']);
     * @return jobID, this is an array[name, id]
     */
    public function dispatchJob($className, $parameters) {
        $input = ['name' => $className, 'parameters' => $parameters, 'requested' => Carbon::now()];
        $jobStatus = $this->jobStatusRepository->create($input);
        $jsID = ['name' => $className, 'id' => $jobStatus->id];
        $parameters['jobID'] = $jobStatus->id;

        // get parent JobStatus from this child's id
        $reflection_class = new \ReflectionClass($className);
        $job = $reflection_class->newInstanceArgs($parameters);
        $this->dispatch($job);

        //dd(__METHOD__.'('.__LINE__.')', compact('className','parameters','input','jobStatus','jsID','job'));
        return $jsID;
    }

    /**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot jobStatus.create, redirect -> home
		if(Entrust::can('jobStatus.create') == false) return redirect()->route('home');

		return view('pages.jobStatus.create');
	}

	/**
	 * Store a new resource
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store() {
		// if guest or cannot jobStatus.create, redirect -> home
		if(Entrust::can('jobStatus.create') == false) return redirect()->route('home');

		$request = Request::all();
		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new JobStatus object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$jobStatus = $this->jobStatusRepository->create($request->all());

		// to see our $jobStatus, we could Dump and Die here
		// dd($jobStatus);

		return redirect()->route('jobStatus.show', ['id' => $jobStatus->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot jobStatus.edit, redirect -> home
		if(Entrust::can('jobStatus.edit') == false) return redirect()->route('home');

		// using an implementation of the JobStatus Repository Interface
		$jobStatus = $this->jobStatusRepository->find($id);

		return view('pages.jobStatus.edit', compact('jobStatus'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id) {
		// if guest or cannot jobStatus.edit, redirect -> home
		if(Entrust::can('jobStatus.edit') == false) return redirect()->route('home');

		$request = Request::all();
		// using an implementation of the JobStatus Repository Interface
		$jobStatus = $this->jobStatusRepository->find($id);
		//$jobStatus->update($request->all());

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();
		unset($input['_method']);
		unset($input['_token']);
		//dd($input);

		$this->jobStatusRepository->update($id, $input);

		return redirect()->route('jobStatus.index');
	}

}
