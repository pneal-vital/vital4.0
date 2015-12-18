<?php namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\LocationRepositoryInterface;
use vital3\Repositories\PalletRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Request;

/**
 * Class LocationController
 * @package App\Http\Controllers
 */
class LocationController extends Controller implements LocationControllerInterface {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\LocationRepositoryInterface
	 */ 
	protected $locationRepository;
	protected $palletRepository;


	/**
	 * Constructor requires Location Repository
	 */ 
	public function __construct(
          LocationRepositoryInterface $locationRepository
        , PalletRepositoryInterface $palletRepository
    ) {
		$this->locationRepository = $locationRepository;
		$this->palletRepository = $palletRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $location = Request::all();
        if(count($location) == 0) {
            // lets provide a default filter
            $location['Status'] = Config::get('constants.location.status.open');
        }

		// using an implementation of the Location Repository Interface
		$locations = $this->locationRepository->paginate($location);

		// Using the view(..) helper function
		return view('pages.location.index', compact('location', 'locations'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$location = Request::all();

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
		//dd($location);

        $levels = $this->buildHeading($location);
        //dd($levels);

        // get children Pallets of this Location
        $filter = [
            'container.parent' => $id,
        ];
        $pallets = $this->palletRepository->paginate($filter);
        //dd($pallets);

		return view('pages.location.show', compact('location', 'levels', 'pallets'));
	}

    /**
     * Get location heading from a child's id.
     */
    public function getHeading($id)
    {
        // get parent Location from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $location = $this->locationRepository->filterOn($filter, $limit = 1);
        //dd($location);

        if(isset($location))
            return $this->buildHeading($location);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($location)
    {
        $levels = [];
        // get parent warehouse of this location id
        //$levels = $this->warehouseController->getHeading($location->objectID);

        $level = (Object) ['name' => 'labels.titles.Location', 'route' => 'location.show', 'title' => $location->Location_Name, 'id' => $location->objectID];
        $levels[] = $level;

        //dd($levels);
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

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Location object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$location = $this->locationRepository->create($request->all());

		// to see our $location, we could Dump and Die here
		// dd($location);

		return redirect()->route('location.show', ['id' => $location->objectID]);
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
	 * Apply the updates to our resource
	 */
	public function update($id, LocationRequest $request) {
		// if guest or cannot location.edit, redirect -> home
		if(Entrust::can('location.edit') == false) return redirect()->route('home');

		// using an implementation of the Location Repository Interface
		$location = $this->locationRepository->find($id);
		//$location->update($request->all());

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();
		unset($input['_method']);
		unset($input['_token']);
		//dd($input);

		$this->locationRepository->update($id, $input);

		return redirect()->route('location.index');
	}

}
