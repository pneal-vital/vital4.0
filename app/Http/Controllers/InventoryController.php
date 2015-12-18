<?php namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\InventoryRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Request;

/**
 * Class InventoryController
 * @package App\Http\Controllers
 */
class InventoryController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\InventoryRepositoryInterface
	 */ 
	protected $inventoryRepository;
	protected $toteController;

	/**
	 * Constructor requires Inventory Repository
	 */ 
	public function __construct(
          InventoryRepositoryInterface $inventoryRepository
        , ToteControllerInterface $toteController
    ) {
		$this->inventoryRepository = $inventoryRepository;
		$this->toteController = $toteController;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $inventory = Request::all();
        if(count($inventory) == 0) {
            // lets provide a default filter
            //$inventory[' .. '] = ..;
        }

		// using an implementation of the Inventory Repository Interface
		$inventories = $this->inventoryRepository->paginate($inventory);
        //dd(__METHOD__."(".__LINE__.")", compact('inventory', 'inventories'));

		// Using the view(..) helper function
		return view('pages.inventory.index', compact('inventory', 'inventories'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$inventory = Request::all();

		// using an implementation of the Inventory Repository Interface
		$inventories = $this->inventoryRepository->paginate($inventory);

		// populate a View
		return View::make('pages.inventory.index', compact('inventory', 'inventories'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {

		// using an implementation of the Inventory Repository Interface
		$inventory = $this->inventoryRepository->find($id);
        //dd($inventory);

        $levels = $this->buildHeading($inventory);
        //dd($levels);

		return view('pages.inventory.show', compact('inventory', 'levels'));
	}

    /**
     * Get inventory heading from a child's id.
     */
    public function getHeading($id)
    {
        // get parent Inventory from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $inventory = $this->inventoryRepository->filterOn($filter, $limit = 1);
        //dd($inventory);

        if(isset($inventory))
            return $this->buildHeading($inventory);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($inventory)
    {
        $levels = [];
        // get parent location of this pallet id
        $levels = $this->toteController->getHeading($inventory->objectID);

        $level = (Object) ['name' => 'labels.titles.Inventory', 'route' => 'inventory.show', 'title' => $inventory->objectID, 'id' => $inventory->objectID];
        $levels[] = $level;

        //dd($levels);
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

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Inventory object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$inventory = $this->inventoryRepository->create($request->all());
		// dd($inventory);

		return redirect()->route('inventory.show', ['id' => $inventory->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot inventory.edit, redirect -> home
		if(Entrust::can('inventory.edit') == false) return redirect()->route('home');

		// using an implementation of the Inventory Repository Interface
		$inventory = $this->inventoryRepository->find($id);

		return view('pages.inventory.edit', compact('inventory'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, InventoryRequest $request) {
		// if guest or cannot inventory.edit, redirect -> home
		if(Entrust::can('inventory.edit') == false) return redirect()->route('home');

		// using an implementation of the Inventory Repository Interface
		$inventory = $this->inventoryRepository->find($id);
        //dd(__METHOD__."(".__LINE__.")",compact('id','request','inventory'));
		$inventory->update($request->all());

		return redirect()->route('inventory.index');
	}

}
