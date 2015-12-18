<?php namespace App\Http\Controllers;

use App\Http\Requests\PerformanceTallyRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use vital40\Repositories\PerformanceTallyRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Request;

/**
 * Class PerformanceTallyController
 * @package App\Http\Controllers
 */
class PerformanceTallyController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\PerformanceTallyRepositoryInterface
	 */ 
	protected $performanceTallyRepository;


	/**
	 * Constructor requires PerformanceTally Repository
	 */ 
	public function __construct(PerformanceTallyRepositoryInterface $performanceTallyRepository) {
		$this->performanceTallyRepository = $performanceTallyRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $performanceTally = Request::all();
        if(count($performanceTally) == 0) {
            // lets provide a default filter
            $performanceTally['dateStamp'] = Carbon::today()->format('Y-m-d');
        }

		// using an implementation of the PerformanceTally Repository Interface
		$performanceTallies = $this->performanceTallyRepository->paginate($performanceTally);

		// Using the view(..) helper function
		return view('pages.performanceTally.index', compact('performanceTally', 'performanceTallies'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$performanceTally = Request::all();

		// using an implementation of the PerformanceTally Repository Interface
		$performanceTallies = $this->performanceTallyRepository->paginate($performanceTally);

		// populate a View
		return View::make('pages.performanceTally.index', compact('performanceTally', 'performanceTallies'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {

		// using an implementation of the PerformanceTally Repository Interface
		$performanceTally = $this->performanceTallyRepository->find($id);
		//dd($performanceTally);

		return view('pages.performanceTally.show', compact('performanceTally'));
	}

    /**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot performanceTally.create, redirect -> home
		if(Entrust::can('performanceTally.create') == false) return redirect()->route('home');

		return view('pages.performanceTally.create');
	}

	/**
	 * Store a new resource
	 * @param PerformanceTallyRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(PerformanceTallyRequest $request) {
		// if guest or cannot performanceTally.create, redirect -> home
		if(Entrust::can('performanceTally.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new PerformanceTally object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$performanceTally = $this->performanceTallyRepository->create($request->all());

		// to see our $performanceTally, we could Dump and Die here
		// dd($performanceTally);

		return redirect()->route('performanceTally.show', ['id' => $performanceTally->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot performanceTally.edit, redirect -> home
		if(Entrust::can('performanceTally.edit') == false) return redirect()->route('home');

		// using an implementation of the PerformanceTally Repository Interface
		$performanceTally = $this->performanceTallyRepository->find($id);

		return view('pages.performanceTally.edit', compact('performanceTally'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, PerformanceTallyRequest $request) {
		// if guest or cannot performanceTally.edit, redirect -> home
		if(Entrust::can('performanceTally.edit') == false) return redirect()->route('home');

		// using an implementation of the PerformanceTally Repository Interface
		$performanceTally = $this->performanceTallyRepository->find($id);
		//$performanceTally->update($request->all());

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();
		unset($input['_method']);
		unset($input['_token']);
		//dd($input);

		$this->performanceTallyRepository->update($id, $input);

		return redirect()->route('performanceTally.index');
	}

}
