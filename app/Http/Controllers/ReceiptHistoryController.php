<?php namespace App\Http\Controllers;

use App\Http\Requests\ReceiptHistoryRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use \Auth;
use \Entrust;
use \Request;

/**
 * Class ReceiptHistoryController
 * @package App\Http\Controllers
 */
class ReceiptHistoryController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\ReceiptHistoryRepositoryInterface
	 */ 
	protected $receiptHistoryRepository;


	/**
	 * Constructor requires ReceiptHistory Repository
	 */ 
	public function __construct(ReceiptHistoryRepositoryInterface $receiptHistoryRepository) {
		$this->receiptHistoryRepository = $receiptHistoryRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
		// if not logged on, redirect -> home
		if(is_null(\Auth::user())) return redirect()->route('home');

        $receiptHistory = Request::all();
        if(count($receiptHistory) == 0) {
            // lets provide a default filter
            $receiptHistory['User_Name' ] = Auth::user()->name;
            $receiptHistory['created_at'] = Carbon::today()->format('Y-m');
        }

		// using an implementation of the ReceiptHistory Repository Interface
		$receiptHistories = $this->receiptHistoryRepository->paginate($receiptHistory);

		// Using the view(..) helper function
		return view('pages.receiptHistory.index', compact('receiptHistory', 'receiptHistories'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		// if not logged on, redirect -> home
		if(is_null(\Auth::user())) return redirect()->route('home');

		$receiptHistory = Request::all();

		// using an implementation of the ReceiptHistory Repository Interface
		$receiptHistories = $this->receiptHistoryRepository->paginate($receiptHistory);

		// populate a View
		return View::make('pages.receiptHistory.index', compact('receiptHistory', 'receiptHistories'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		// if not logged on, redirect -> home
		if(is_null(\Auth::user())) return redirect()->route('home');

		// using an implementation of the ReceiptHistory Repository Interface
		$receiptHistory = $this->receiptHistoryRepository->find($id);
		//dd($receiptHistory);

		return view('pages.receiptHistory.show', compact('receiptHistory'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot receiptHistory.create, redirect -> home
		if(Entrust::can('receiptHistory.create') == false) return redirect()->route('home');

		return view('pages.receiptHistory.create');
	}

	/**
	 * Store a new resource
	 * @param ReceiptHistoryRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(ReceiptHistoryRequest $request) {
		// if guest or cannot receiptHistory.create, redirect -> home
		if(Entrust::can('receiptHistory.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new ReceiptHistory object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$receiptHistory = $this->receiptHistoryRepository->create($request->all());

		// to see our $receiptHistory, we could Dump and Die here
		// dd($receiptHistory);

		return redirect()->route('receiptHistory.show', ['id' => $receiptHistory->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot receiptHistory.edit, redirect -> home
		if(Entrust::can('receiptHistory.edit') == false) return redirect()->route('home');

		// using an implementation of the ReceiptHistory Repository Interface
		$receiptHistory = $this->receiptHistoryRepository->find($id);

		return view('pages.receiptHistory.edit', compact('receiptHistory'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, ReceiptHistoryRequest $request) {
		// if guest or cannot receiptHistory.edit, redirect -> home
		if(Entrust::can('receiptHistory.edit') == false) return redirect()->route('home');

		// using an implementation of the ReceiptHistory Repository Interface
		//$receiptHistory = ReceiptHistory::find($id);
		//$receiptHistory->update($request->all());

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();
		//unset($input['_method']);
		//unset($input['_token']);
		//dd($input);

		$this->receiptHistoryRepository->update($id, $input);

		return redirect()->route('receiptHistory.index');
	}

}
