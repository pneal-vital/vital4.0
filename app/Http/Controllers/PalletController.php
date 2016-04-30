<?php namespace App\Http\Controllers;

use App\Http\Requests\PalletRequest;
use App\vital3\Location;
use App\vital3\Pallet;
use App\vital40\Inventory\ComingleRulesInterface;
use Illuminate\Support\Facades\View;
use vital3\Repositories\LocationRepositoryInterface;
use vital3\Repositories\PalletRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use \Config;
use \Entrust;
use \Lang;
use \Log;
use \Redirect;
use \Session;

/**
 * Class PalletController
 * @package App\Http\Controllers
 */
class PalletController extends Controller implements PalletControllerInterface {

    use SaveRequest;
    use DBTransaction;

    /**
     * Reference an implementation of the Repository Interface
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;
	protected $palletRepository;
	protected $toteRepository;
    protected $locationController;
    protected $comingleRules;

	/**
	 * Constructor requires Pallet Repository
	 */ 
	public function __construct(
          LocationRepositoryInterface $locationRepository
        , PalletRepositoryInterface $palletRepository
        , ToteRepositoryInterface $toteRepository
        , LocationControllerInterface $locationController
        , ComingleRulesInterface $comingleRules
    ) {
		$this->locationRepository = $locationRepository;
		$this->palletRepository = $palletRepository;
		$this->toteRepository = $toteRepository;
        $this->locationController = $locationController;
        $this->comingleRules = $comingleRules;

        $this->setConnection(Pallet::CONNECTION_NAME);
	}

    protected function defaultRequest() {
        $defaultRequest = [];
        // lets provide a default filter
        $defaultRequest['Status'] = Config::get('constants.pallet.status.lock');
        return $defaultRequest;
    }

    /**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $pallet = $this->getRequest(Pallet::TABLE_NAME);

		// using an implementation of the Pallet Repository Interface
		$pallets = $this->palletRepository->paginate($pallet);

		// Using the view(..) helper function
		return view('pages.pallet.index', compact('pallet', 'pallets'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$pallet = $this->getRequest(Pallet::TABLE_NAME);

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
		//dd(__METHOD__.'('.__LINE__.')',compact('id','pallet'));

        $levels = $this->buildHeading($pallet);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','pallet','levels'));

        // get children Totes of this Pallet
        $filter = [
            'container.parent' => $id,
        ];
        $totes = $this->toteRepository->paginate($filter);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','pallet','levels','totes'));

		return view('pages.pallet.show', compact('pallet', 'levels', 'totes'));
	}

    /**
     * Get pallet heading from a child's id.
     */
    public function getHeading($id) {
        // get parent Pallet from this child's id
        $filter = [
            'container.child' => $id,
        ];
        $pallet = $this->palletRepository->filterOn($filter, $limit = 1);
        //dd(__METHOD__.'('.__LINE__.')',compact('id','filter','pallet'));

        if(isset($pallet))
            return $this->buildHeading($pallet);
        return [];
    }

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($pallet) {
        // get parent location of this pallet id
        $levels = $this->locationController->getHeading($pallet->objectID);

        $level = (Object) ['name' => 'labels.titles.Pallet', 'route' => 'pallet.show', 'title' => $pallet->Pallet_ID, 'id' => $pallet->objectID];
        $levels[] = $level;

        //dd(__METHOD__.'('.__LINE__.')',compact('pallet','level','levels'));
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

        if(isset($request->btn_Cancel)) return Redirect::route('pallet.index');

        $this->transaction(function($this) use($request, &$pallet) {
            /*
             *  retrieve all the request form field values
             *  and pass them into create to mass update the new Pallet object
             *  Can replace Request::all() in the call to create, because we added validation.
             */
            $pallet = $this->palletRepository->create($request->all());
        });

		// to see our $pallet, we could Dump and Die here
		//dd(__METHOD__.'('.__LINE__.')',compact('request','pallet'));

        // below not needed if we are using Redirect::route('pallet.edit' ..
        //$location = $this->getRequest(Location::TABLE_NAME);

//TODO learn how to write library functions like: str_begins($pallet->Pallet_ID, ['FWP', 'RES']);
        if(isset($pallet->Pallet_ID) and strlen($pallet->Pallet_ID) > 3
            and (substr($pallet->Pallet_ID, 0, 3) == 'FWP' or substr($pallet->Pallet_ID, 0, 3) == 'RES')) {
            return redirect()->route('pallet.show', ['id' => $pallet->objectID])
                ->with(['status' => Lang::get('internal.created', ['class' => Pallet::TABLE_SYNONYM])]);
        }

        /*
         * Below works except the flash message stays around for more than one page.
         * See: http://stackoverflow.com/questions/24579580/laravel-session-flash-persists-for-2-requests
         *   suggests to use redirect after the flash,
         *   but then you don't need to flash, you can just say ->with(your message ..)
         * Testing shows that Session::flash(..) and Redirect::route(..) works
         *   just as Redirect::route(..)->with(..) works.
         */
        Session::flash('status', Lang::get('internal.created', ['class' => Pallet::TABLE_NAME]));
        Session::flash('warning', Lang::get('internal.errors.noParent').' '.Lang::get('labels.titles.Move_Pallet'));
        //return view('pages.pallet.edit', compact('pallet', 'location'));
        return Redirect::route('pallet.edit', ['id' => $pallet->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot pallet.edit, redirect -> home
		if(Entrust::can('pallet.edit') == false) return redirect()->route('home');

		// using an implementation of the Pallet Repository Interface
		$pallet = $this->palletRepository->find($id);
		$locations = $this->locationRepository->filterOn(['container.child' => $id]);
        if(isset($locations) and count($locations) == 1) {
            $inLocation = $locations[0];
        } else {
            $location = $this->getRequest(Location::TABLE_NAME);
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('id','pallet','inLocation','location'));

		return view('pages.pallet.edit', compact('pallet', 'location', 'inLocation'));
	}

	/**
	 * Move this resource
	 */
	public function move($id) {
		// if guest or cannot pallet.edit, redirect -> home
		if(Entrust::can('pallet.edit') == false) return redirect()->route('home');

        $location = $this->getRequest(Location::TABLE_NAME);
        //$sessionAll = Session::all();
        //dd(__METHOD__.'('.__LINE__.')',compact('id','location','sessionAll'));

		// using an implementation of the Pallet Repository Interface
		$pallet = $this->palletRepository->find($id);
        $locations = $this->locationRepository->paginate($location);

		return view('pages.pallet.edit', compact('pallet', 'location', 'locations'));
	}

	/**
	 * Locate this resource
	 */
	public function locate($pltID, $id) {
		// if guest or cannot pallet.edit, redirect -> home
		if(Entrust::can('pallet.edit') == false) return redirect()->route('home');

        // using an implementation of the Pallet Repository Interface
        $pallet = $this->palletRepository->find($pltID);
        $inLocation = $this->locationRepository->find($id);
        //dd(__METHOD__.'('.__LINE__.')',compact('pltID','id','pallet','inLocation'));

        // update container set parentID = $id where objectID = $pltID;
        $result = $this->locationController->putPalletIntoLocation($pltID, $id);
        if($result !== true) return Redirect::back()->withErrors($result)->withInput();

        return redirect()->route('pallet.show', ['id' => $pltID]);
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, PalletRequest $request) {
		// if guest or cannot pallet.edit, redirect -> home
		if(Entrust::can('pallet.edit') == false) return redirect()->route('home');

        //dd(__METHOD__.'('.__LINE__.')',compact('id','request'));
        if(isset($request->btn_Cancel)) return redirect()->route('pallet.show', ['id' => $id]);

        $this->transaction(function($this) use($id, $request) {
            // using an implementation of the Pallet Repository Interface
            $this->palletRepository->update($id, $request->all());
        });

        // FWP and RES carts don't need to be located, redirect to show
        $pallet = $this->palletRepository->find($id);
        //Log::debug('pallet->Pallet_ID: >'.$pallet->Pallet_ID.'<');
//TODO learn how to write library functions like: str_begins($pallet->Pallet_ID, ['FWP', 'RES']);
        if(isset($pallet->Pallet_ID) and strlen($pallet->Pallet_ID) > 3
        and (substr($pallet->Pallet_ID, 0, 3) == 'FWP' or substr($pallet->Pallet_ID, 0, 3) == 'RES')) {
            return redirect()->route('pallet.show', ['id' => $id])
                ->with(['status' => Lang::get('internal.updated', ['class' => Pallet::TABLE_SYNONYM])]);
        } else {
            // when our pallet is located, redirect to show
            $locations = $this->locationRepository->filterOn(['container.child' => $id]);
            if(isset($locations) and count($locations) > 0)
                return redirect()->route('pallet.show', ['id' => $id])
                    ->with(['status' => Lang::get('internal.updated', ['class' => Pallet::TABLE_NAME])]);
        }
        return redirect()->route('pallet.edit', ['id' => $id])
            ->with(['status' => Lang::get('internal.updated', ['class' => Pallet::TABLE_NAME])
                 , 'warning' => Lang::get('internal.errors.noParent').' '.Lang::get('labels.titles.Move_Pallet')]);
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
        $pallet = $this->palletRepository->find($id);
        $deleted = false;

        if(isset($pallet)) {
            /*
             * In the case of a Pallet delete request
             * 1. make sure there are no Totes on this Pallet
             * ok to delete
             */
            $totes = $this->toteRepository->filterOn(['container.parent' => $id]);
            Log::debug('Totes: '.(isset($totes) ? count($totes) : 'none' ));
            if(isset($totes) and count($totes) > 0) {
                $children = Lang::get('labels.titles.Totes');
                $model = Lang::get('labels.titles.Pallet');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__.'('.__LINE__.')',compact('id','pallet','totes'));

            $this->transaction(function($this) use($id, &$deleted) {
                $deleted = $this->palletRepository->delete($id);
            });
        }

        Log::debug('deleted: '.($deleted ? 'yes' : 'no'));
        return Redirect::route('pallet.index');
    }

	/**
	 * Put a tote onto a pallet.
     * ComingleRules verifies move this tote into this pallet is allowed.
	 *
	 * Returns true if it was successful, otherwise returns an error message.
	 */
	public function putToteIntoPallet($toteID, $palletID) {
		Log::debug("putToteIntoPallet( $toteID, $palletID )");
		$result = $this->comingleRules->isPutToteIntoPalletAllowed($toteID, $palletID);
		Log::debug($result);
		if($result === true) {
			// update container set parentID = $id where objectID = $pltID;
            $result = $this->transaction(function($this) use($toteID, $palletID) {
                return $this->palletRepository->putToteIntoPallet($toteID, $palletID);
            });
		}
		return $result;
	}

}
