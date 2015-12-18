<?php namespace App\Http\Controllers;

use App\Http\Requests\UserActivityRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use vital40\Repositories\UserActivityRepositoryInterface;
use \Auth;
use \Entrust;
use \Request;


/**
 * Class UserActivityController
 * @package App\Http\Controllers
 */
class UserActivityController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\UserActivityRepositoryInterface
	 */ 
	protected $userActivityRepository;


	/**
	 * Constructor requires UserActivity Repository
	 */ 
	public function __construct(UserActivityRepositoryInterface $userActivityRepository) {
		$this->userActivityRepository = $userActivityRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
		// if not logged on, redirect -> home
		if(is_null(Auth::user())) return redirect()->route('home');

        $userActivity = Request::all();
        if(count($userActivity) == 0) {
            // lets provide a default filter
            $userActivity['User_Name' ] = Auth::user()->name;
			$userActivity['created_at'] = Carbon::today()->format('Y-m');
        }

		// using an implementation of the UserActivity Repository Interface
		$userActivities = $this->userActivityRepository->paginate($userActivity);

		// Using the view(..) helper function
		return view('pages.userActivity.index', compact('userActivity', 'userActivities'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		// if not logged on, redirect -> home
		if(is_null(Auth::user())) return redirect()->route('home');

		$userActivity = Request::all();
        //if(!Entrust::hasRole(['receiptSuper','receiptManager','support']))
        if(!Entrust::hasRole(['teamLead','super','manager','support']))
            $userActivity['User_Name'] = Auth::user()->name;
        //dd($userActivity);

		// using an implementation of the UserActivity Repository Interface
		$userActivities = $this->userActivityRepository->paginate($userActivity);

		// populate a View
		return View::make('pages.userActivity.index', compact('userActivity', 'userActivities'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		// if not logged on, redirect -> home
		if(is_null(Auth::user())) return redirect()->route('home');

		// using an implementation of the UserActivity Repository Interface
		$userActivity = $this->userActivityRepository->find($id);
		//dd($userActivity);

		return view('pages.userActivity.show', compact('userActivity'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot userActivity.create, redirect -> home
		if(Entrust::can('userActivity.create') == false) return redirect()->route('home');

		return view('pages.userActivity.create');
	}

	/**
	 * Store a new resource
	 * @param UserActivityRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(UserActivityRequest $request) {
		// if guest or cannot userActivity.create, redirect -> home
		if(Entrust::can('userActivity.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new UserActivity object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$userActivity = $this->userActivityRepository->create($request->all());

		// to see our $userActivity, we could Dump and Die here
		// dd($userActivity);

		return redirect()->route('userActivity.show', ['id' => $userActivity->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot userActivity.edit, redirect -> home
		if(Entrust::can('userActivity.edit') == false) return redirect()->route('home');

		// using an implementation of the UserActivity Repository Interface
		$userActivity = $this->userActivityRepository->find($id);

		return view('pages.userActivity.edit', compact('userActivity'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, UserActivityRequest $request) {
		// if guest or cannot userActivity.edit, redirect -> home
		if(Entrust::can('userActivity.edit') == false) return redirect()->route('home');

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();
		//unset($input['_method']);
		//unset($input['_token']);
		//dd($input);

		$this->userActivityRepository->update($id, $input);

		return redirect()->route('userActivity.index');
	}

}
