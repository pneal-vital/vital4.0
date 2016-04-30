<?php namespace App\Http\Controllers;

use App\Http\Controllers\SaveRequest;
use App\Http\Requests\WarehouseRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\WarehouseRepositoryInterface;
use vital3\Repositories\LocationRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Log;
use \Redirect;
use \Request;

/**
 * Class WarehouseController
 * @package App\Http\Controllers
 */
class WarehouseController extends Controller implements WarehouseControllerInterface {

	use SaveRequest;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\WarehouseRepositoryInterface
	 */
	protected $warehouseRepository;
	protected $locationRepository;

	/**
	 * Constructor requires Warehouse Repository
	 */ 
	public function __construct(
          WarehouseRepositoryInterface $warehouseRepository
        , LocationRepositoryInterface $locationRepository
    ) {
		$this->warehouseRepository = $warehouseRepository;
		$this->locationRepository = $locationRepository;
	}

	protected function defaultRequest() {
		$defaultRequest = [];
		// lets provide a default filter
        $defaultRequest['Warehouse_Code'] = 'CALEDON';
		return $defaultRequest;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $warehouse = $this->getRequest('Warehouse');

		// using an implementation of the Warehouse Repository Interface
		$warehouses = $this->warehouseRepository->paginate($warehouse);

		// Using the view(..) helper function
		return view('pages.warehouse.index', compact('warehouse', 'warehouses'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$warehouse = $this->getRequest('Warehouse');

		// using an implementation of the Warehouse Repository Interface
		$warehouses = $this->warehouseRepository->paginate($warehouse);

		// populate a View
		return View::make('pages.warehouse.index', compact('warehouse', 'warehouses'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		// using an implementation of the Warehouse Repository Interface
		$warehouse = $this->warehouseRepository->find($id);
		//dd(__METHOD__."(".__LINE__.")",compact('id','warehouse'));

        $levels = $this->buildHeading($warehouse);
        //dd(__METHOD__."(".__LINE__.")",compact('id','warehouse','levels'));

        // get children Locations of this Warehouse
        $filter = [
            'container.parent' => $id,
        ];
        $locations = $this->locationRepository->paginate($filter);
        //dd(__METHOD__."(".__LINE__.")",compact('id','warehouse','levels','filter','locations'));

		return view('pages.warehouse.show', compact('warehouse', 'levels', 'locations'));
	}

    /**
     * Get warehouse heading from a child's id.
     */
    public function getHeading($id) {
        // get parent Warehouse from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $warehouse = $this->warehouseRepository->filterOn($filter, $limit = 1);
        //dd(__METHOD__."(".__LINE__.")",compact('id','warehouse'));

        if(isset($warehouse))
            return $this->buildHeading($warehouse);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($warehouse) {
        $levels = [];
        // get parent warehouse of this warehouse id
        //$levels = $this->warehouseController->getHeading($warehouse->objectID);

        $level = (Object) ['name' => 'labels.titles.Warehouse', 'route' => 'warehouse.show', 'title' => $warehouse->Warehouse_Name, 'id' => $warehouse->objectID];
        $levels[] = $level;

        //dd(__METHOD__."(".__LINE__.")",compact('warehouse','level','levels'));
        return $levels;
    }

    /**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot warehouse.create, redirect -> home
		if(Entrust::can('warehouse.create') == false) return redirect()->route('home');

		return view('pages.warehouse.create');
	}

	/**
	 * Store a new resource
	 * @param WarehouseRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(WarehouseRequest $request) {
		// if guest or cannot warehouse.create, redirect -> home
		if(Entrust::can('warehouse.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Warehouse object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$warehouse = $this->warehouseRepository->create($request->all());

		// to see our $warehouse, we could Dump and Die here
		// dd(__METHOD__."(".__LINE__.")",compact('request','warehouse'));

		return redirect()->route('warehouse.show', ['id' => $warehouse->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot warehouse.edit, redirect -> home
		if(Entrust::can('warehouse.edit') == false) return redirect()->route('home');

		// using an implementation of the Warehouse Repository Interface
		$warehouse = $this->warehouseRepository->find($id);

		return view('pages.warehouse.edit', compact('warehouse'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, WarehouseRequest $request) {
		// if guest or cannot warehouse.edit, redirect -> home
		if(Entrust::can('warehouse.edit') == false) return redirect()->route('home');

		// using an implementation of the Warehouse Repository Interface
		$this->warehouseRepository->update($id, $request->all());

		return redirect()->route('warehouse.show', ['id' => $id]);
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
        Log::debug('id: '.$id);
        $warehouse = $this->warehouseRepository->find($id);
        $deleted = false;

        if(isset($warehouse)) {
            /*
             * In the case of a Warehouse delete request
             * 1. make sure there are no Locations in this Warehouse
             * ok to delete
             */
            $locations = $this->locationRepository->filterOn(['container.parent' => $id]);
            Log::debug('Locations: '.(isset($locations) ? count($locations) : 'none' ));
            if(isset($locations) and count($locations) > 0) {
                $children = Lang::get('labels.titles.Locations');
                $model = Lang::get('labels.titles.Warehouse');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__."(".__LINE__.")",compact('id','warehouse','locations'));

            Log::debug('delete: '.$id);
            $deleted = $this->warehouseRepository->delete($id);
        }

        Log::debug('deleted: '.($deleted ? 'yes' : 'no'));
        return $this->index();
    }

}
