<?php namespace App\Http\Controllers\vital40;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SaveRequest;
use App\Http\Requests\UPCRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\ClientRepositoryInterface;
use vital3\Repositories\InventoryRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use \Auth;
use \Entrust;
use \Lang;
use \Log;
use \Redirect;
use \Request;
use \Session;

/**
 * Class UPCController
 * @package App\Http\Controllers
 */
class UPCController extends Controller {

    use SaveRequest;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\UPCRepositoryInterface
	 */ 
	protected $clientRepository;
    protected $inventoryRepository;
    protected $purchaseOrderDetailRepository;
	protected $upcRepository;

    /**
	 * Constructor requires UPC Repository
	 */ 
	public function __construct(
          ClientRepositoryInterface $clientRepository
        , InventoryRepositoryInterface $inventoryRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , UPCRepositoryInterface $upcRepository
    ) {
		$this->clientRepository = $clientRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
		$this->upcRepository = $upcRepository;
    }

    protected function defaultRequest() {
        $defaultRequest = [];
        // lets provide a default filter
        $client = $this->clientRepository->filterOn(['Client_Name' => 'LCL'], 1);
        $defaultRequest['Client_Code'] = $client->objectID;
        return $defaultRequest;
    }

    /**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $upc = $this->getRequest('UPC');

		// using an implementation of the UPC Repository Interface
		$upcs = $this->upcRepository->paginate($upc);

		// Using the view(..) helper function
		return view('pages.upc.index', compact('upc', 'upcs'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$upc = $this->getRequest('UPC');

		// using an implementation of the UPC Repository Interface
		$upcs = $this->upcRepository->paginate($upc);

		// populate a View
		return View::make('pages.upc.index', compact('upc', 'upcs'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		// using an implementation of the UPC Repository Interface
		$upc = $this->upcRepository->find($id);

        //dd(__METHOD__."(".__LINE__.")",compact('upc'));
        return view('pages.upc.show', compact('upc'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot upc.create, redirect -> home
		if(Entrust::can('upc.create') == false) return redirect()->route('home');

		return view('pages.upc.create');
	}

	/**
	 * Store a new resource
	 * @param UPCRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(UPCRequest $request) {
		// if guest or cannot upc.create, redirect -> home
		if(Entrust::can('upc.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new UPC object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$upc = $this->upcRepository->create($request->all());

		return redirect()->route('upc.index');
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot upc.edit, redirect -> home
		if(Entrust::can('upc.edit') == false) return redirect()->route('home');

		// using an implementation of the UPC Repository Interface
		$upc = $this->upcRepository->find($id);

		return view('pages.upc.edit', compact('upc'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, UPCRequest $request) {
		// if guest or cannot upc.edit, redirect -> home
		if(Entrust::can('upc.edit') == false) return redirect()->route('home');

		// using an implementation of the UPC Repository Interface
		$this->upcRepository->update($id, $request->all());

		return redirect()->route('upc.index');
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
        Log::debug(__METHOD__."(".__LINE__."):  id: ".$id);
        $upc = $this->upcRepository->find($id);
        $deleted = false;

        if(isset($upc)) {
            /*
             * In the case of an UPC delete request
             * All we have to do is make sure there are no Inventory records that reference this UPC
             * ok to delete
             */
            $inventories = $this->inventoryRepository->filterOn(['Item' => $id]);
            Log::debug(__METHOD__."(".__LINE__."):  Inventories: ".(isset($inventories) ? count($inventories) : 'none' ));
            if(isset($inventories) and count($inventories) > 0) {
                $children = Lang::get('labels.titles.Inventories');
                $model = Lang::get('labels.titles.UPC');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__."(".__LINE__.")",compact('id','upc','inventories'));

            Log::debug(__METHOD__."(".__LINE__."):  delete: ".$id);
            $deleted = $this->upcRepository->delete($id);
        }

        Log::debug(__METHOD__."(".__LINE__."):  deleted: ".($deleted ? 'yes' : 'no'));
        return $this->index();
    }

}
