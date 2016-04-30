<?php namespace App\Http\Controllers;

use App\Http\Requests\ToteRequest;
use App\vital3\GenericContainer;
use App\vital40\Inventory\ComingleRulesInterface;
use Illuminate\Support\Facades\View;
use vital3\Repositories\InventoryRepositoryInterface;
use vital3\Repositories\PalletRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Log;
use \Redirect;
use \Session;

/**
 * Class ToteController
 * @package App\Http\Controllers
 */
class ToteController extends Controller implements ToteControllerInterface {

	use SaveRequest;
    use DBTransaction;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var InventoryRepositoryInterface
	 */ 
    protected $inventoryRepository;
    protected $palletRepository;
    protected $toteRepository;
    protected $palletController;
    protected $comingleRules;

	/**
	 * Constructor requires Tote Repository
	 */ 
	public function __construct(
          InventoryRepositoryInterface $inventoryRepository
        , PalletRepositoryInterface $palletRepository
        , ToteRepositoryInterface $toteRepository
        , PalletControllerInterface $palletController
        , ComingleRulesInterface $comingleRules
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->palletRepository = $palletRepository;
		$this->toteRepository = $toteRepository;
        $this->palletController = $palletController;
        $this->comingleRules = $comingleRules;

        $this->setConnection(GenericContainer::CONNECTION_NAME);
	}

    protected function defaultRequest() {
        $defaultRequest = [];
        // lets provide a default filter
        $defaultRequest['Status'] = Config::get('constants.tote.status.open');
        return $defaultRequest;
    }

    /**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $tote = $this->getRequest(GenericContainer::TABLE_SYNONYM);

		// using an implementation of the Tote Repository Interface
		$totes = $this->toteRepository->paginate($tote);

		// Using the view(..) helper function
		return view('pages.tote.index', compact('tote', 'totes'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$tote = $this->getRequest(GenericContainer::TABLE_SYNONYM);

		// using an implementation of the Tote Repository Interface
		$totes = $this->toteRepository->paginate($tote);

		// populate a View
		return View::make('pages.tote.index', compact('tote', 'totes'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		// using an implementation of the Tote Repository Interface
		$tote = $this->toteRepository->find($id);
		//dd(__METHOD__.'('.__LINE__.')',compact('id','tote'));

        $levels = $this->buildHeading($tote);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','tote','levels'));

        // get children Inventories of this Tote
        $filter = [
            'container.parent' => $id,
        ];
        $inventories = $this->inventoryRepository->paginate($filter);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','tote','levels','inventories'));

		return view('pages.tote.show', compact('tote', 'levels', 'inventories'));
	}

    /**
     * Get tote heading from a child's id.
     */
    public function getHeading($id) {
        // get parent Tote from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $tote = $this->toteRepository->filterOn($filter, $limit = 1);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','tote'));

        if(isset($tote))
            return $this->buildHeading($tote);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($tote) {
        // get parent location of this pallet id
        $levels = $this->palletController->getHeading($tote->objectID);

        $level = (Object) ['name' => 'labels.titles.Tote', 'route' => 'tote.show', 'title' => $tote->Carton_ID, 'id' => $tote->objectID];
        $levels[] = $level;

        //dd(__METHOD__.'('.__LINE__.')',compact('tote','level','levels'));
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

        if(isset($request->btn_Cancel)) return Redirect::route('tote.index');

        $this->transaction(function($this) use($request, &$tote) {
            /*
             *  retrieve all the request form field values
             *  and pass them into create to mass update the new Tote object
             *  Can replace Request::all() in the call to create, because we added validation.
             */
            $tote = $this->toteRepository->create($request->all());
        });

		// to see our $tote, we could Dump and Die here
		//dd(__METHOD__.'('.__LINE__.')',compact('request','tote'));

        $pallet = $this->getRequest('Pallet');

        Session::flash('status', Lang::get('internal.created', ['class' => GenericContainer::TABLE_SYNONYM]));
        Session::flash('warning', Lang::get('internal.errors.noParent').' '.Lang::get('labels.titles.Move_Tote'));
        return view('pages.tote.edit', compact('tote', 'pallet'));
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot tote.edit, redirect -> home
		if(Entrust::can('tote.edit') == false) return redirect()->route('home');

		// using an implementation of the Tote Repository Interface
		$tote = $this->toteRepository->find($id);
        $pallets = $this->palletRepository->filterOn(['container.child' => $id]);
        if(isset($pallets) and count($pallets) == 1) {
            $inPallet = $pallets[0];
        } else {
            $pallet = $this->getRequest('Pallet');
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('id','tote','inPallet','pallet'));

        return view('pages.tote.edit', compact('tote', 'pallet', 'inPallet'));
	}

    /**
     * Move this resource
     */
    public function move($id) {
        // if guest or cannot tote.edit, redirect -> home
        if(Entrust::can('tote.edit') == false) return redirect()->route('home');

        $pallet = $this->getRequest('Pallet');
        //$sessionAll = Session::all();
        //dd(__METHOD__.'('.__LINE__.')',compact('id','pallet','sessionAll'));

        // using an implementation of the Pallet Repository Interface
        $tote = $this->toteRepository->find($id);
        $pallets = $this->palletRepository->paginate($pallet);

        return view('pages.tote.edit', compact('tote', 'pallet', 'pallets'));
    }

    /**
     * Locate this resource
     */
    public function locate($gcID, $id) {
        // if guest or cannot tote.edit, redirect -> home
        if(Entrust::can('tote.edit') == false) return redirect()->route('home');

        // using an implementation of the Pallet Repository Interface
        $tote = $this->toteRepository->find($gcID);
        $inPallet = $this->palletRepository->find($id);
        //dd(__METHOD__.'('.__LINE__.')',compact('pltID','id','tote','inPallet'));

        // update container set parentID = $id where objectID = $pltID;
        $result = $this->palletController->putToteIntoPallet($gcID, $id);
        if($result !== true) return Redirect::back()->withErrors($result)->withInput();

        return view('pages.tote.edit', compact('tote', 'inPallet'));
    }

    /**
	 * Apply the updates to our resource
	 */
	public function update($id, ToteRequest $request) {
		// if guest or cannot tote.edit, redirect -> home
		if(Entrust::can('tote.edit') == false) return redirect()->route('home');

        if(isset($request->btn_Cancel)) return redirect()->route('tote.show', ['id' => $id]);

        $this->transaction(function($this) use($id, $request) {
            $this->toteRepository->update($id, $request->all());
        });

        // when our tote is located, redirect to show
        $pallets = $this->palletRepository->filterOn(['container.child' => $id]);
        if(isset($pallets) and count($pallets) > 0)
            return redirect()->route('tote.show', ['id' => $id])
                ->with(['status' => Lang::get('internal.updated', ['class' => GenericContainer::TABLE_SYNONYM])]);

        return redirect()->route('tote.edit', ['id' => $id])
            ->with(['status' => Lang::get('internal.updated', ['class' => GenericContainer::TABLE_SYNONYM])
                 , 'warning' => Lang::get('internal.errors.noParent').' '.Lang::get('labels.titles.Move_Tote')]);
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
        $tote = $this->toteRepository->find($id);
        $deleted = false;

        if(isset($tote)) {
            /*
             * In the case of a Tote delete request
             * 1. make sure there are no Inventory records in this Tote
             * ok to delete
             */
            $inventories = $this->inventoryRepository->filterOn(['container.parent' => $id]);
            Log::debug('Inventories: '.(isset($inventories) ? count($inventories) : 'none' ));
            if(isset($inventories) and count($inventories) > 0) {
                $children = Lang::get('labels.titles.Inventories');
                $model = Lang::get('labels.titles.Tote');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__.'('.__LINE__.')',compact('id','tote','inventories'));

            $this->transaction(function($this) use($id, &$deleted) {
                $deleted = $this->toteRepository->delete($id);
            });
        }

        Log::debug('deleted: '.($deleted ? 'yes' : 'no'));
        return Redirect::route('tote.index');
    }

    /**
     * Put a inventory into a tote.
     * ComingleRules verifies move this inventory into this tote is allowed.
     *
     * Returns true if it was successful, otherwise returns an error message.
     */
    public function putInventoryIntoTote($inventoryID, $toteID){
        Log::debug("putInventoryIntoTote( $inventoryID, $toteID )");
        $result = $this->comingleRules->isPutInventoryIntoToteAllowed($inventoryID, $toteID);
        Log::debug($result);
        if($result === true) {
            // update container set parentID = $id where objectID = $pltID;
            $result = $this->transaction(function($this) use($inventoryID, $toteID) {
                return $this->toteRepository->putInventoryIntoTote($inventoryID, $toteID);
            });
        }
        return $result;
    }

}
