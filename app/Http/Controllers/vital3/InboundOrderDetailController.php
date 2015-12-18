<?php namespace App\Http\Controllers\vital3;

use App\Http\Controllers\Controller;
use App\Http\Requests\InboundOrderDetailRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\InboundOrderDetailRepositoryInterface;
use \Entrust;
use \Lang;
use \Request;


/**
 * Class InboundOrderDetailController
 * @package App\Http\Controllers
 */
class InboundOrderDetailController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\InboundOrderDetailRepositoryInterface
	 */ 
	protected $inboundOrderDetailRepository;

	/**
	 * Constructor requires InboundOrderDetail Repository
	 */ 
	public function __construct(
          InboundOrderDetailRepositoryInterface $inboundOrderDetailRepository
    ) {
		$this->inboundOrderDetailRepository = $inboundOrderDetailRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $inboundOrderDetail = Request::all();
        if(count($inboundOrderDetail) == 0) {
            // lets provide a default filter
            //$inboundOrderDetail[' .. '] = ..;
        }

		// using an implementation of the InboundOrderDetail Repository Interface
		$inboundOrderDetails = $this->inboundOrderDetailRepository->paginate($inboundOrderDetail);

		// Using the view(..) helper function
		return view('pages.inboundOrderDetail.index', compact('inboundOrderDetail', 'inboundOrderDetails'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$inboundOrderDetail = Request::all();

		// using an implementation of the InboundOrderDetail Repository Interface
		$inboundOrderDetails = $this->inboundOrderDetailRepository->paginate($inboundOrderDetail);

		// populate a View
		return View::make('pages.inboundOrderDetail.index', compact('inboundOrderDetail', 'inboundOrderDetails'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {
		// using an implementation of the InboundOrderDetail Repository Interface
		$inboundOrderDetail = $this->inboundOrderDetailRepository->find($id);

		return view('pages.inboundOrderDetail.show', compact('inboundOrderDetail'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot inboundOrderDetail.create, redirect -> home
		if(Entrust::can('inboundOrderDetail.create') == false) return redirect()->route('home');

		return view('pages.inboundOrderDetail.create');
	}

	/**
	 * Store a new resource
	 * @param InboundOrderDetailRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(InboundOrderDetailRequest $request) {
		// if guest or cannot inboundOrderDetail.create, redirect -> home
		if(Entrust::can('inboundOrderDetail.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new InboundOrderDetail object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$inboundOrderDetail = $this->inboundOrderDetailRepository->create($request->all());

		// to see our $inboundOrderDetail, we could Die and Dump here
		// dd($inboundOrderDetail);

		return redirect()->route('inboundOrderDetail.show', ['id' => $inboundOrderDetail->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot inboundOrderDetail.edit, redirect -> home
		if(Entrust::can('inboundOrderDetail.edit') == false) return redirect()->route('home');

		// using an implementation of the InboundOrderDetail Repository Interface
		$inboundOrderDetail = $this->inboundOrderDetailRepository->find($id);

		return view('pages.inboundOrderDetail.edit', compact('inboundOrderDetail'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, InboundOrderDetailRequest $request) {
		// if guest or cannot inboundOrderDetail.edit, redirect -> home
		if(Entrust::can('inboundOrderDetail.edit') == false) return redirect()->route('home');

		// using an implementation of the InboundOrderDetail Repository Interface
		$inboundOrderDetail = $this->inboundOrderDetailRepository->find($id);
		$inboundOrderDetail->update($request->all());

		return redirect()->route('inboundOrderDetail.index');
	}

}
