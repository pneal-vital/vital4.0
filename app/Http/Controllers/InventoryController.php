<?php namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\vital3\Inventory;
use App\vital40\Inventory\ComingleRulesInterface;
use Illuminate\Support\Facades\View;
use vital3\Repositories\InventoryRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Log;
use \Redirect;
use \Session;

/**
 * Class InventoryController
 * @package App\Http\Controllers
 */
class InventoryController extends Controller {

	use SaveRequest;
    use DBTransaction;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var InventoryRepositoryInterface
	 */ 
	protected $inventoryRepository;
	protected $toteRepository;
	protected $toteController;
    protected $comingleRules;

	/**
	 * Constructor requires Inventory Repository
	 */ 
	public function __construct(
          InventoryRepositoryInterface $inventoryRepository
        , ToteRepositoryInterface $toteRepository
        , ToteControllerInterface $toteController
        , ComingleRulesInterface $comingleRules
    ) {
		$this->inventoryRepository = $inventoryRepository;
		$this->toteRepository = $toteRepository;
		$this->toteController = $toteController;
        $this->comingleRules = $comingleRules;

        $this->setConnection(Inventory::CONNECTION_NAME);
	}

    protected function defaultRequest() {
        $defaultRequest = [];
        // lets provide a default filter
        $defaultRequest['Status'] = Config::get('constants.inventory.status.open');
        return $defaultRequest;
    }

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $inventory = $this->getRequest(Inventory::TABLE_NAME);

		// using an implementation of the Inventory Repository Interface
		$inventories = $this->inventoryRepository->paginate($inventory);
        //dd(__METHOD__.'('.__LINE__.')', compact('inventory', 'inventories'));

		// Using the view(..) helper function
		return view('pages.inventory.index', compact('inventory', 'inventories'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$inventory = $this->getRequest(Inventory::TABLE_NAME);

		// using an implementation of the Inventory Repository Interface
		$inventories = $this->inventoryRepository->paginate($inventory);
        //dd(__METHOD__.'('.__LINE__.')', compact('inventory', 'inventories'));

		// populate a View
		return View::make('pages.inventory.index', compact('inventory', 'inventories'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {
		// using an implementation of the Inventory Repository Interface
		$inventory = $this->inventoryRepository->find($id);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','inventory'));

        $levels = $this->buildHeading($inventory);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','inventory','levels'));

//TODO should show UPC Detail one liner, plus link to upc.show
//TODO should show ?? Order Detail one liner, plus link to ?od.show

		return view('pages.inventory.show', compact('inventory', 'levels'));
	}

    /**
     * Get inventory heading from a child's id.
     */
    public function getHeading($id) {
        // get parent Inventory from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $inventory = $this->inventoryRepository->filterOn($filter, $limit = 1);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','inventory'));

        if(isset($inventory))
            return $this->buildHeading($inventory);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($inventory) {
        // get parent location of this pallet id
        $levels = $this->toteController->getHeading($inventory->objectID);

        $level = (Object) ['name' => 'labels.titles.Inventory', 'route' => 'inventory.show', 'title' => $inventory->objectID, 'id' => $inventory->objectID];
        $levels[] = $level;

        //dd(__METHOD__.'('.__LINE__.')',compact('inventory','level','levels'));
        return $levels;
    }

    /**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot inventory.create, redirect -> home
		if(Entrust::can('inventory.create') == false) return redirect()->route('home');

		return view('pages.inventory.create');
	}

	/**
	 * Store a new resource
	 * @param InventoryRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(InventoryRequest $request) {
		// if guest or cannot inventory.create, redirect -> home
		if(Entrust::can('inventory.create') == false) return redirect()->route('home');

        if(isset($request->btn_Cancel)) return Redirect::route('inventory.index');

        $this->transaction(function($this) use($request, &$inventory) {
            /*
             *  retrieve all the request form field values
             *  and pass them into create to mass update the new Inventory object
             *  Can replace Request::all() in the call to create, because we added validation.
             */
            $inventory = $this->inventoryRepository->create($request->all());
        });

        // to see our $inventory, we could Dump and Die here
		//dd(__METHOD__.'('.__LINE__.')',compact('request','inventory'));

        $tote = $this->getRequest('Tote');

        Session::flash('status', Lang::get('internal.created', ['class' => Inventory::TABLE_NAME]));
        Session::flash('warning', Lang::get('internal.errors.noParent').' '.Lang::get('labels.titles.Move_Tote'));
        return view('pages.inventory.edit', compact('inventory', 'tote'));
	}

    /**
     * Retrieve an existing resource for edit
     */
    public function edit($id) {
        // if guest or cannot inventory.edit, redirect -> home
        if(Entrust::can('inventory.edit') == false) return redirect()->route('home');

        // using an implementation of the Inventory Repository Interface
        $inventory = $this->inventoryRepository->find($id);
        $totes = $this->toteRepository->filterOn(['container.child' => $id]);
        if(isset($totes) and count($totes) == 1) {
            $inTote = $totes[0];
        } else {
            $tote = $this->getRequest('Tote');
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('id','inventory','inTote','tote'));

        return view('pages.inventory.edit', compact('inventory', 'tote', 'inTote'));
    }

    /**
     * Move this resource
     */
    public function move($id) {
        // if guest or cannot inventory.edit, redirect -> home
        if(Entrust::can('inventory.edit') == false) return redirect()->route('home');

        $tote = $this->getRequest('Tote');
        //$sessionAll = Session::all();
        //dd(__METHOD__.'('.__LINE__.')',compact('id','tote','sessionAll'));

        // using an implementation of the Tote Repository Interface
        $inventory = $this->inventoryRepository->find($id);
        $totes = $this->toteRepository->paginate($tote);

        return view('pages.inventory.edit', compact('inventory', 'tote', 'totes'));
    }

    /**
     * Locate this resource
     */
    public function locate($invID, $id) {
        // if guest or cannot inventory.edit, redirect -> home
        if(Entrust::can('inventory.edit') == false) return redirect()->route('home');

        // using an implementation of the Tote Repository Interface
        $inventory = $this->inventoryRepository->find($invID);
        $inTote = $this->toteRepository->find($id);
        //dd(__METHOD__.'('.__LINE__.')',compact('pltID','id','inventory','inTote'));

        // update container set parentID = $id where objectID = $pltID;
        $result =  $this->toteController->putInventoryIntoTote($invID, $id);
        if($result !== true) return Redirect::back()->withErrors($result)->withInput();

        return view('pages.inventory.edit', compact('inventory', 'inTote'));
    }

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, InventoryRequest $request) {
		// if guest or cannot inventory.edit, redirect -> home
		if(Entrust::can('inventory.edit') == false) return redirect()->route('home');

        if(isset($request->btn_Cancel)) return redirect()->route('inventory.show', ['id' => $id]);

        $this->transaction(function($this) use($id, $request) {
            $this->inventoryRepository->update($id, $request->all());
        });

        // when our inventory is located, redirect to show
        $totes = $this->toteRepository->filterOn(['container.child' => $id]);
        if(isset($totes) and count($totes) > 0)
            return redirect()->route('inventory.show', ['id' => $id])
                ->with(['status' => Lang::get('internal.updated', ['class' => Inventory::TABLE_NAME])]);

		return redirect()->route('inventory.edit', ['id' => $id])
            ->with(['status' => Lang::get('internal.updated', ['class' => Inventory::TABLE_NAME])
                 , 'warning' => Lang::get('internal.errors.noParent').' '.Lang::get('labels.titles.Move_Inventory')]);
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
        $inventory = $this->inventoryRepository->find($id);
        $deleted = false;

        if(isset($inventory)) {
            /*
             * In the case of a Inventory delete request
             * 1. make sure it's not allocated to an Outbound_Order_Detail line
             * ok to delete
             */
//TODO define OutboundOrderDetail
            /*
            $oods = $this->outboundOrderDetailRepository->filterOn(['id' => $inventory->Order_Line]);
            Log::debug('oods: '.(isset($oods) ? count($oods) : 'none' ));
            if(isset($oods) and count($oods) > 0) {
                $children = Lang::get('labels.titles.Outbound_Order_Details');
                $model = Lang::get('labels.titles.Inventory');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__.'('.__LINE__.')',compact('id','inventory','oods'));
            */

            $this->transaction(function($this) use($id, &$deleted) {
                $deleted = $this->inventoryRepository->delete($id);
            });
        }

        Log::debug('deleted: '.($deleted ? 'yes' : 'no'));
        return Redirect::route('inventory.index');
    }

}
