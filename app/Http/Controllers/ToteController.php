<?php namespace App\Http\Controllers;

use App\Http\Requests\ToteRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\InventoryRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Request;


/**
 * Class ToteController
 * @package App\Http\Controllers
 */
class ToteController extends Controller implements ToteControllerInterface {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\ToteRepositoryInterface
	 */ 
    protected $inventoryRepository;
    protected $toteRepository;
    protected $palletController;

	/**
	 * Constructor requires Tote Repository
	 */ 
	public function __construct(
          InventoryRepositoryInterface $inventoryRepository
        , ToteRepositoryInterface $toteRepository
        , PalletControllerInterface $palletController
    ) {
        $this->inventoryRepository = $inventoryRepository;
		$this->toteRepository = $toteRepository;
        $this->palletController = $palletController;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $tote = Request::all();
        if(count($tote) == 0) {
            // lets provide a default filter
            $tote['Status'] = Config::get('constants.tote.status.open');
		}
        //dd(__METHOD__."(".__LINE__.")",compact('tote'));

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status')] + Lang::get('lists.tote.status');

		// using an implementation of the Tote Repository Interface
		$totes = $this->toteRepository->paginate($tote);

		// Using the view(..) helper function
		return view('pages.tote.index', compact('tote', 'statuses', 'totes'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$tote = Request::all();

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status')] + Lang::get('lists.tote.status');

		// using an implementation of the Tote Repository Interface
		$totes = $this->toteRepository->paginate($tote);

		// populate a View
		return View::make('pages.tote.index', compact('tote', 'statuses', 'totes'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {

		// using an implementation of the Tote Repository Interface
		$tote = $this->toteRepository->find($id);
		//dd($tote);

        $levels = $this->buildHeading($tote);
        //dd($levels);

        // get children Inventories of this Tote
        $filter = [
            'container.parent' => $id,
        ];
        $inventories = $this->inventoryRepository->paginate($filter);
        //dd($inventories);

		return view('pages.tote.show', compact('tote', 'levels', 'inventories'));
	}

    /**
     * Get tote heading from a child's id.
     */
    public function getHeading($id)
    {
        // get parent Tote from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $tote = $this->toteRepository->filterOn($filter, $limit = 1);
        //dd($tote);

        if(isset($tote))
            return $this->buildHeading($tote);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($tote)
    {
        $levels = [];
        // get parent location of this pallet id
        $levels = $this->palletController->getHeading($tote->objectID);

        $level = (Object) ['name' => 'labels.titles.Tote', 'route' => 'tote.show', 'title' => $tote->Carton_ID, 'id' => $tote->objectID];
        $levels[] = $level;

        //dd($levels);
        return $levels;
    }

    /**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot tote.create, redirect -> home
		if(Entrust::can('tote.create') == false) return redirect()->route('home');

		return view('pages.tote.create');
	}

	/**
	 * Store a new resource
	 * @param ToteRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(ToteRequest $request) {
		// if guest or cannot tote.create, redirect -> home
		if(Entrust::can('tote.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Tote object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$tote = $this->toteRepository->create($request->all());

		// to see our $tote, we could Dump and Die here
		// dd($tote);

		return redirect()->route('tote.show', ['id' => $tote->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot tote.edit, redirect -> home
		if(Entrust::can('tote.edit') == false) return redirect()->route('home');

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status')] + Lang::get('lists.tote.status');

		// using an implementation of the Tote Repository Interface
		$tote = $this->toteRepository->find($id);

		return view('pages.tote.edit', compact('tote', 'statuses'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, ToteRequest $request) {
		// if guest or cannot tote.edit, redirect -> home
		if(Entrust::can('tote.edit') == false) return redirect()->route('home');

		// using an implementation of the Tote Repository Interface
		$tote = $this->toteRepository->find($id);
		//$tote->update($request->all());

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();
		unset($input['_method']);
		unset($input['_token']);
		//dd($input);

		$this->toteRepository->update($id, $input);

		return redirect()->route('tote.index');
	}

}
