<?php namespace App\Http\Controllers\Receive;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use vital3\Repositories\LocationRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use \Auth;
use \Config;
use \Entrust;
use \Log;
use \ReceiveLocation;
use \Request;

/**
 * Class ReceiveLocationController
 * @package App\Http\Controllers
 */
class ReceiveLocationController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\LocationRepositoryInterface
	 */
	protected $locationRepository;
	protected $userActivityRepository;

	/**
	 * Constructor requires location Repository
	 */
	public function __construct(
          LocationRepositoryInterface $locationRepository
        , UserActivityRepositoryInterface $userActivityRepository
    ) {
		$this->locationRepository = $locationRepository;
		$this->userActivityRepository = $userActivityRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        Log::debug(__METHOD__.'('.__LINE__.')');

        // Do we know Location?
        $filter = [
            'classID'   => Config::get('constants.userActivity.classID.ReceiveLocation'),
            'User_Name' => Auth::user()->name,
        ];
        $userActivity = $this->userActivityRepository->filterOn($filter, $limit = 1);
        if(isset($userActivity)) {
            $location = $this->locationRepository->find($userActivity->id);
        }
        if(isset($location)) {
            return $this->show($location->objectID);
        }

		// lets provide a default filter
		$location = [
			'Location_Name' => 'Receiv',
            'LocType'       => 'ACTIVITY',
		];
		// using an implementation of the location Repository Interface
		$locations = $this->locationRepository->paginate($location);

		// Using the view(..) helper function
		return view('pages.receiveLocation.index', compact('location', 'locations'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::can('receive') == false) return redirect()->route('home');

		$location = Request::all();

		// using an implementation of the location Repository Interface
		$locations = $this->locationRepository->paginate($location);

		// populate a View
		return View::make('pages.receiveLocation.index', compact('location', 'locations'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {
        if(Entrust::can('receive') == false) return redirect()->route('home');

		// using an implementation of the location Repository Interface
		$location = $this->locationRepository->find($id);

		// calling into business logic
		ReceiveLocation::associate($location);

		return view('pages.receiveLocation.show', compact('location'));
	}

}
