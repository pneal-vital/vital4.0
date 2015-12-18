<?php namespace App\Http\Controllers;

use App\Http\Requests\PalletRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\PalletRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Request;


/**
 * Class PalletController
 * @package App\Http\Controllers
 */
class PalletController extends Controller implements PalletControllerInterface {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\PalletRepositoryInterface
	 */ 
	protected $palletRepository;
	protected $locationController;
	protected $toteRepository;


	/**
	 * Constructor requires Pallet Repository
	 */ 
	public function __construct(
          PalletRepositoryInterface $palletRepository
        , LocationControllerInterface $locationController
        , ToteRepositoryInterface $toteRepository
    ) {
		$this->palletRepository = $palletRepository;
		$this->locationController = $locationController;
		$this->toteRepository = $toteRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $pallet = Request::all();
        if(count($pallet) == 0) {
            // lets provide a default filter
            $pallet['Status'] = Config::get('constants.pallet.status.lock');
        }

		// using an implementation of the Pallet Repository Interface
		$pallets = $this->palletRepository->paginate($pallet);

		// Using the view(..) helper function
		return view('pages.pallet.index', compact('pallet', 'pallets'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$pallet = Request::all();

		// using an implementation of the Pallet Repository Interface
		$pallets = $this->palletRepository->paginate($pallet);

		// populate a View
		return View::make('pages.pallet.index', compact('pallet', 'pallets'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {

		// using an implementation of the Pallet Repository Interface
		$pallet = $this->palletRepository->find($id);
		//dd($pallet);

        $levels = $this->buildHeading($pallet);
        //dd($levels);

        // get children Totes of this Pallet
        $filter = [
            'container.parent' => $id,
        ];
        $totes = $this->toteRepository->paginate($filter);
        //dd($totes);

		return view('pages.pallet.show', compact('pallet', 'levels', 'totes'));
	}

    /**
     * Get pallet heading from a child's id.
     */
    public function getHeading($id)
    {
        // get parent Pallet from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $pallet = $this->palletRepository->filterOn($filter, $limit = 1);
        //dd($pallet);

        if(isset($pallet))
            return $this->buildHeading($pallet);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($pallet)
    {
        $levels = [];
        // get parent location of this pallet id
        $levels = $this->locationController->getHeading($pallet->objectID);

        $level = (Object) ['name' => 'labels.titles.Pallet', 'route' => 'pallet.show', 'title' => $pallet->Pallet_ID, 'id' => $pallet->objectID];
        $levels[] = $level;

        //dd($levels);
        return $levels;
    }

    /**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot pallet.create, redirect -> home
		if(Entrust::can('pallet.create') == false) return redirect()->route('home');

		return view('pages.pallet.create');
	}

	/**
	 * Store a new resource
	 * @param PalletRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(PalletRequest $request) {
		// if guest or cannot pallet.create, redirect -> home
		if(Entrust::can('pallet.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Pallet object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$pallet = $this->palletRepository->create($request->all());

		// to see our $pallet, we could Dump and Die here
		// dd($pallet);

		return redirect()->route('pallet.show', ['id' => $pallet->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot pallet.edit, redirect -> home
		if(Entrust::can('pallet.edit') == false) return redirect()->route('home');

		// using an implementation of the Pallet Repository Interface
		$pallet = $this->palletRepository->find($id);

		return view('pages.pallet.edit', compact('pallet'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, PalletRequest $request) {
		// if guest or cannot pallet.edit, redirect -> home
		if(Entrust::can('pallet.edit') == false) return redirect()->route('home');

		// using an implementation of the Pallet Repository Interface
		$pallet = $this->palletRepository->find($id);
		//$pallet->update($request->all());

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();
		unset($input['_method']);
		unset($input['_token']);
		//dd($input);

		$this->palletRepository->update($id, $input);

		return redirect()->route('pallet.index');
	}

}
