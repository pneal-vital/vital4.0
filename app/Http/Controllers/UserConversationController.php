<?php namespace App\Http\Controllers;

use App\Http\Requests\UserConversationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use vital40\Repositories\UserActivityRepositoryInterface;
use vital40\Repositories\UserConversationRepositoryInterface;
use \Auth;
use \Entrust;
use \Request;

/**
 * Class UserConversationController
 * @package App\Http\Controllers
 */
class UserConversationController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\UserConversationRepositoryInterface
	 */ 
	protected $userConversationRepository;
	protected $userActivityRepository;


	/**
	 * Constructor requires UserConversation Repository
	 */ 
	public function __construct(
          UserConversationRepositoryInterface $userConversationRepository
        , UserActivityRepositoryInterface $userActivityRepository
    ) {
		$this->userConversationRepository = $userConversationRepository;
		$this->userActivityRepository = $userActivityRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
		// if not logged on, redirect -> home
		if(is_null(Auth::user())) return redirect()->route('home');

        // TODO default to the POD and Article user is currently associated with
        //$article = $this->userActivityRepository->getUserActivities(...)

        $userConversation = Request::all();
        if(count($userConversation) == 0) {
            // lets provide a default filter
            $userConversation['User_Name' ] = Auth::user()->name;
            $userConversation['created_at'] = Carbon::today()->format('Y-m');
        }

		// using an implementation of the UserConversation Repository Interface
		$userConversations = $this->userConversationRepository->paginate($userConversation);

		// Using the view(..) helper function
		return view('pages.userConversation.index', compact('userConversation', 'userConversations'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		// if not logged on, redirect -> home
		if(is_null(Auth::user())) return redirect()->route('home');

		$userConversation = Request::all();
        //if(!Entrust::hasRole(['receiptSuper','receiptManager','support']))
        if(!Entrust::hasRole(['teamLead','super','manager','support']))
            $userConversation['User_Name'] = Auth::user()->name;

		// using an implementation of the UserConversation Repository Interface
		$userConversations = $this->userConversationRepository->paginate($userConversation);

		// populate a View
		return View::make('pages.userConversation.index', compact('userConversation', 'userConversations'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		// if not logged on, redirect -> home
		if(is_null(Auth::user())) return redirect()->route('home');

		// using an implementation of the UserConversation Repository Interface
		$userConversation = $this->userConversationRepository->find($id);
		//dd($userConversation);

		return view('pages.userConversation.show', compact('userConversation'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot userConversation.create, redirect -> home
		if(Entrust::can('userConversation.create') == false) return redirect()->route('home');

		return view('pages.userConversation.create');
	}

	/**
	 * Store a new resource
	 * @param UserConversationRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(UserConversationRequest $request) {
		// if guest or cannot userConversation.create, redirect -> home
		if(Entrust::can('userConversation.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new UserConversation object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$userConversation = $this->userConversationRepository->create($request->all());

		// to see our $userConversation, we could Dump and Die here
		// dd($userConversation);

		return redirect()->route('userConversation.show', ['id' => $userConversation->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot userConversation.edit, redirect -> home
		if(Entrust::can('userConversation.edit') == false) return redirect()->route('home');

		// using an implementation of the UserConversation Repository Interface
		$userConversation = $this->userConversationRepository->find($id);

		return view('pages.userConversation.edit', compact('userConversation'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, UserConversationRequest $request) {
		// if guest or cannot userConversation.edit, redirect -> home
		if(Entrust::can('userConversation.edit') == false) return redirect()->route('home');

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();
		//unset($input['_method']);
		//unset($input['_token']);
		//dd($input);

		$this->userConversationRepository->update($id, $input);

		return redirect()->route('userConversation.index');
	}

}
