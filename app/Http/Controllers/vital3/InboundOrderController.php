<?php namespace App\Http\Controllers\vital3;

use App\Http\Controllers\Controller;
use App\Http\Requests\InboundOrderRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\ClientRepositoryInterface;
use vital3\Repositories\InboundOrderDetailRepositoryInterface;
use vital3\Repositories\InboundOrderRepositoryInterface;
use \Entrust;
use \Lang;
use \Request;

/**
 * Class InboundOrderController
 * @package App\Http\Controllers
 */
class InboundOrderController extends Controller {

    /**
     * Reference an implementation of the Repository Interface
     * @var vital3\Repositories\InboundOrderRepositoryInterface
     */
    protected $clientRepository;
    protected $inboundOrderDetailRepository;
    protected $inboundOrderRepository;

	/**
	 * Constructor requires InboundOrder Repository
	 */ 
	public function __construct(
          ClientRepositoryInterface $clientRepository
        , InboundOrderDetailRepositoryInterface $inboundOrderDetailRepository
        , InboundOrderRepositoryInterface $inboundOrderRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->inboundOrderDetailRepository = $inboundOrderDetailRepository;
        $this->inboundOrderRepository = $inboundOrderRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $inboundOrder = Request::all();
        if(count($inboundOrder) == 0) {
            // lets provide a default filter
            $client = $this->clientRepository->filterOn(['Client_Name' => 'LCL'], 1);
            $inboundOrder['Client'] = $client->objectID;
        }

		// using an implementation of the InboundOrder Repository Interface
		$inboundOrders = $this->inboundOrderRepository->paginate($inboundOrder);

		// Using the view(..) helper function
		return view('pages.inboundOrder.index', compact('inboundOrder', 'inboundOrders'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$inboundOrder = Request::all();

		// using an implementation of the InboundOrder Repository Interface
		$inboundOrders = $this->inboundOrderRepository->paginate($inboundOrder);

		// populate a View
		return View::make('pages.inboundOrder.index', compact('inboundOrder', 'inboundOrders'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {

		// using an implementation of the InboundOrder Repository Interface
		$inboundOrder = $this->inboundOrderRepository->find($id);

		// then get any _Additional data elements
		$inboundOrderAdditional = $this->inboundOrderRepository->getAdditional($id);

		// get children InboundOrderDetails for this InboundOrder
		$filter = [
			'Order_Number' => $inboundOrder->objectID,
		];
		$inboundOrderDetails = $this->inboundOrderDetailRepository->paginate($filter);
		//dd($inboundOrderDetails);

		return view('pages.inboundOrder.show', compact('inboundOrder','inboundOrderAdditional', 'inboundOrderDetails'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot inboundOrder.create, redirect -> home
		if(Entrust::can('inboundOrder.create') == false) return redirect()->route('home');

		/*
		 * Flash overlay
		 * this will overlay again when an error message is presented
		 */
		flash()->overlay('So, you want to create a new InboundOrder.', 'Be careful!');

		return view('pages.inboundOrder.create');
	}

	/**
	 * Store a new resource
	 * @param InboundOrderRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(InboundOrderRequest $request) {
		// if guest or cannot inboundOrder.create, redirect -> home
		if(Entrust::can('inboundOrder.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new InboundOrder object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$inboundOrder = $this->inboundOrderRepository->create($request->all());

		// to see our $inboundOrder, we could Die and Dump here
		// dd($inboundOrder);

		/*
		 * Flash a message to the user that the Inbound_Order was created
		 * - Flash -> show only once
		 * - flash_message_important - provides an [X] button for user to cancel message,
		 *                             else message self cancels after 3 seconds
		session()->flash('flash_message', 'InboundOrder '.$inboundOrder->objectID.' was created successfully!');
		session()->flash('flash_message_important', true);
		 */
		/*
		 * using Laracasts/Flash, the above lines can be replaced with
		flash('InboundOrder '.$inboundOrder->objectID.' was created successfully!')
		flash()->info('..'), flash()->success('..'), flash()->error('..'), flash()->warning('..') or flash()->overlay('..')
		 * or
		\Flash::message('..'), \Flash::info('..'), \Flash::success('..'), \Flash::error('..'), \Flash::warning('..') or \Flash::overlay('message', 'heading')
		 *
		 * below example has the [X] button but will slideUp after 3 seconds
		 */
		flash()->success('InboundOrder '.$inboundOrder->objectID.' was created successfully!');

		return redirect()->route('inboundOrder.show', ['id' => $inboundOrder->objectID]);

		/*
		 * the Flash could also be written as
		return redirect()->route('inboundOrder.show', ['id' => $inboundOrder->objectID])
			->with([
				'flash_message' => 'InboundOrder '.$inboundOrder->objectID.' was created successfully!',
		        'flash_message_important' => true,
			]);
		 */

	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot inboundOrder.edit, redirect -> home
		if(Entrust::can('inboundOrder.edit') == false) return redirect()->route('home');

		// using an implementation of the InboundOrder Repository Interface
		$inboundOrder = $this->inboundOrderRepository->find($id);

		// then get any _Additional data elements
		$inboundOrderAdditional = $this->inboundOrderRepository->getAdditional($id);

		return view('pages.inboundOrder.edit', compact('inboundOrder','inboundOrderAdditional'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, InboundOrderRequest $request) {
		// if guest or cannot inboundOrder.edit, redirect -> home
		if(Entrust::can('inboundOrder.edit') == false) return redirect()->route('home');

		// using an implementation of the InboundOrder Repository Interface
		$inboundOrder = $this->inboundOrderRepository->find($id);
		$inboundOrder->update($request->all());

		/*
		 * Flash a message to the user that the Inbound_Order was created
		 * - Flash -> show only once
		 * - message will self cancels after 3 seconds
		session()->flash('flash_message', 'InboundOrder '.$inboundOrder->objectID.' was updated successfully');
		 */
		/*
		 * using Laracasts/Flash, the above lines can be replaced with
		flash('InboundOrder '.$inboundOrder->objectID.' was created successfully!') == flash()->info('..')
		flash()->success('..'), flash()->error('..'), flash()->warning('..') or flash()->overlay('..')
		 * or
		\Flash::message('..'), \Flash::info('..'), \Flash::success('..'), \Flash::error('..'), \Flash::warning('..') or \Flash::overlay('message', 'heading')
		 *
		 * below example has the [X] button and will slideUp after 3 seconds
		 */
		flash('InboundOrder '.$inboundOrder->objectID.' was updated successfully');

		// then get any _Additional data elements
		$inboundOrderAdditional = $this->inboundOrderRepository->getAdditional($id);
		//$inboundOrderAdditional->update($request->all());

		return redirect()->route('inboundOrder.index');
	}

}
