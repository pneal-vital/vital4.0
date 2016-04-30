<?php namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\vital3\Location;
use App\vital40\Inventory\ComingleRulesInterface;
use Illuminate\Support\Facades\View;
use vital3\Repositories\LocationRepositoryInterface;
use vital3\Repositories\PalletRepositoryInterface;
use vital3\Repositories\WarehouseRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Log;
use \Redirect;

/**
 * Class LocationController
 * @package App\Http\Controllers
 */
class LocationController extends Controller implements LocationControllerInterface {

	use SaveRequest;
    use DBTransaction;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var LocationRepositoryInterface
	 */
	protected $locationRepository;
	protected $palletRepository;
	protected $warehouseRepository;
    protected $warehouseController;
    protected $comingleRules;

    /**
	 * Constructor requires Location Repository
	 */ 
	public function __construct(
          LocationRepositoryInterface $locationRepository
        , PalletRepositoryInterface $palletRepository
        , WarehouseRepositoryInterface $warehouseRepository
        , WarehouseControllerInterface $warehouseController
        , ComingleRulesInterface $comingleRules
    ) {
        $this->locationRepository = $locationRepository;
        $this->palletRepository = $palletRepository;
        $this->warehouseRepository = $warehouseRepository;
		$this->warehouseController = $warehouseController;
		$this->comingleRules = $comingleRules;

        $this->setConnection(Location::CONNECTION_NAME);
	}

	protected function defaultRequest() {
		$defaultRequest = [];
		// lets provide a default filter
        $defaultRequest['Status'] = Config::get('constants.location.status.open');
		return $defaultRequest;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $location = $this->getRequest(Location::TABLE_NAME);

		// using an implementation of the Location Repository Interface
		$locations = $this->locationRepository->paginate($location);

		// Using the view(..) helper function
        //dd(__METHOD__.'('.__LINE__.')',compact('location','locations'));
		return view('pages.location.index', compact('location', 'locations'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$location = $this->getRequest(Location::TABLE_NAME);

		// using an implementation of the Location Repository Interface
		$locations = $this->locationRepository->paginate($location);

		// populate a View
		return View::make('pages.location.index', compact('location', 'locations'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		// using an implementation of the Location Repository Interface
		$location = $this->locationRepository->find($id);
		//dd(__METHOD__.'('.__LINE__.')',compact('id','location'));

        $levels = $this->buildHeading($location);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','location','levels'));

        // get children Pallets of this Location
        $filter = [
            'container.parent' => $id,
        ];
        $pallets = $this->palletRepository->paginate($filter);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','location','levels','filter','pallets'));

		return view('pages.location.show', compact('location', 'levels', 'pallets'));
	}

    /**
     * Get location heading from a child's id.
     */
    public function getHeading($id) {
        // get parent Location from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $location = $this->locationRepository->filterOn($filter, $limit = 1);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','location'));

        if(isset($location))
            return $this->shallowBuildHeading($location);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function shallowBuildHeading($location) {
        $levels = [];
        // get parent warehouse of this location id
        //$levels = $this->warehouseController->getHeading($location->objectID);

        $level = (Object) ['name' => 'labels.titles.Location', 'route' => 'location.show', 'title' => $location->Location_Name, 'id' => $location->objectID];
        $levels[] = $level;

        //dd(__METHOD__.'('.__LINE__.')',compact('location','level','levels'));
        return $levels;
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($location) {
        // get parent warehouse of this location id
        $levels = $this->warehouseController->getHeading($location->objectID);

        $level = (Object) ['name' => 'labels.titles.Location', 'route' => 'location.show', 'title' => $location->Location_Name, 'id' => $location->objectID];
        $levels[] = $level;

        //dd(__METHOD__.'('.__LINE__.')',compact('location','level','levels'));
        return $levels;
    }

    /**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot location.create, redirect -> home
		if(Entrust::can('location.create') == false) return redirect()->route('home');

		return view('pages.location.create');
	}

	/**
	 * Store a new resource
	 * @param LocationRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(LocationRequest $request) {
		// if guest or cannot location.create, redirect -> home
		if(Entrust::can('location.create') == false) return redirect()->route('home');

        if(isset($request->btn_Cancel)) return Redirect::route('location.index');

        $this->transaction(function($this) use($request, &$location) {
            /*
             *  retrieve all the request form field values
             *  and pass them into create to mass update the new Location object
             *  Can replace Request::all() in the call to create, because we added validation.
             */
            $location = $this->locationRepository->create($request->all());

            // temp code until a second warehouse is created
            $warehouses = $this->warehouseRepository->filterOn(['Warehouse_Code' => 'CALEDON']);
            $this->doLocate($location->objectID, $warehouses[0]->objectID);
        });

        // to see our $location, we could Dump and Die here
		//dd(__METHOD__.'('.__LINE__.')',compact('request','location','warehouses'));

		return redirect()->route('location.show', ['id' => $location->objectID])
            ->with(['status' => Lang::get('internal.created', ['class' => Location::TABLE_NAME])]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot location.edit, redirect -> home
		if(Entrust::can('location.edit') == false) return redirect()->route('home');

		// using an implementation of the Location Repository Interface
		$location = $this->locationRepository->find($id);

		return view('pages.location.edit', compact('location'));
	}

    /**
     * Locate this resource
     */
    public function locate($locID, $id) {
        // if guest or cannot location.edit, redirect -> home
        if(Entrust::can('location.edit') == false) return redirect()->route('home');

        // using an implementation of the Location Repository Interface
        $location = $this->locationRepository->find($locID);
        $inWarehouse = $this->warehouseRepository->find($id);
        //dd(__METHOD__.'('.__LINE__.')',compact('locID','id','location','inWarehouse'));

        $this->transaction(function($this) use($locID, $id) {
            $this->doLocate($locID, $id);
        });

        return view('pages.location.edit', compact('location', 'inWarehouse'));
    }

    /**
     * Locate this resource
     */
    protected function doLocate($locID, $id) {
        // update container set parentID = $id where objectID = $pltID;
        Log::debug("Locate Location $locID into Warehouse $id");
        return $this->warehouseRepository->putLocationIntoWarehouse($locID, $id);
    }

    /**
	 * Apply the updates to our resource
	 */
	public function update($id, LocationRequest $request) {
		// if guest or cannot location.edit, redirect -> home
		if(Entrust::can('location.edit') == false) return redirect()->route('home');

        if(isset($request->btn_Cancel)) return redirect()->route('location.show', ['id' => $id]);

        $this->transaction(function($this) use($id, $request) {
            // using an implementation of the Location Repository Interface
            $this->locationRepository->update($id, $request->all());

            // temp code until a second warehouse is created
            $warehouses = $this->warehouseRepository->filterOn(['Warehouse_Code' => 'CALEDON']);
            $this->doLocate($id, $warehouses[0]->objectID);
        });

		return redirect()->route('location.show', ['id' => $id])
            ->with(['status' => Lang::get('internal.updated', ['class' => Location::TABLE_NAME])]);
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
        $location = $this->locationRepository->find($id);
        $deleted = false;

        if(isset($location)) {
            /*
             * In the case of a Location delete request
             * 1. make sure there are no Pallets in this Location
             * ok to delete
             */
            $pallets = $this->palletRepository->filterOn(['container.parent' => $id]);
            Log::debug('Pallets: '.(isset($pallets) ? count($pallets) : 'none' ));
            if(isset($pallets) and count($pallets) > 0) {
                $children = Lang::get('labels.titles.Pallets');
                $model = Lang::get('labels.titles.Location');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__.'('.__LINE__.')',compact('id','location','pallets'));

            $this->transaction(function($this) use($id, &$deleted) {
                $deleted = $this->locationRepository->delete($id);
            });
        }

        Log::debug('deleted: '.($deleted ? 'yes' : 'no'));
        return Redirect::route('location.index');
    }

    /**
     * Put a pallet into a Location.
     * ComingleRules verifies move this pallet into this Location is allowed.
     *
     * Returns true if it was successful, otherwise returns an error message.
     */
	public function putPalletIntoLocation($palletID, $locationID) {
        Log::info("putPalletIntoLocation( $palletID, $locationID )");
        $result = $this->comingleRules->isPutPalletIntoLocationAllowed($palletID, $locationID);
        Log::debug($result);
        if($result === true) {
            // update container set parentID = $id where objectID = $pltID;
            $result = $this->transaction(function($this) use($palletID, $locationID) {
                return $this->locationRepository->putPalletIntoLocation($palletID, $locationID);
            });
        }
        return $result;
    }

}
