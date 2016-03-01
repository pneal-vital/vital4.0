<?php namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\View;
use vital40\Repositories\UserRepositoryInterface;
use vital40\Repositories\RoleUserRepositoryInterface;
use \Auth;
use \Entrust;
use \Lang;
use \Log;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller {

    use SaveRequest;

	/**
	 * Reference an implementation of the Repository Interface
	 */
	protected $roleUserRepository;
	protected $userRepository;

	/**
	 * Constructor requires User Repository
	 */ 
	public function __construct(
		  RoleUserRepositoryInterface $roleUserRepository
		, UserRepositoryInterface $userRepository
    ) {
		$this->roleUserRepository = $roleUserRepository;
		$this->userRepository = $userRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.view'))) return redirect()->route('home');

        $user = $this->getRequest('User');

		// using an implementation of the User Repository Interface
		$users = $this->userRepository->paginate($user);

		// Using the view(..) helper function
		return view('pages.user.index', compact('user', 'users'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.view'))) return redirect()->route('home');

		$user = $this->getRequest('User');

		// using an implementation of the User Repository Interface
		$users = $this->userRepository->paginate($user);

		// populate a View
		return View::make('pages.user.index', compact('user', 'users'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
        if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.view'))) return redirect()->route('home');

		// using an implementation of the User Repository Interface
		$user = $this->userRepository->find($id);
        $userRoles = $this->roleUserRepository->filterOn([ 'user_id' => $id ], 0);
        $roles = array();
        foreach($userRoles as $userRole) {
            $roles[] = $userRole->role;
        }

        //dd(__METHOD__.'('.__LINE__.')',compact('id','user','userRoles','roles'));
        return view('pages.user.show', compact('user', 'roles'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
        if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.create'))) return redirect()->route('home');
        Log::debug('create');

		return view('pages.user.create');
	}

	/**
	 * Store a new resource
	 * @param UserRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(UserRequest $request) {
        if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.create'))) return redirect()->route('home');
        Log::info('save new:', $request->all());

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new User object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$user = $this->userRepository->create($request->all());

		// to see our $user, we could Dump and Die here
        // dd(__METHOD__.'('.__LINE__.')',compact('request','user'));
		return redirect()->route('user.show', ['id' => $user->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
        if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.edit'))) return redirect()->route('home');
        Log::debug('edit: '.$id);

		// using an implementation of the User Repository Interface
		$user = $this->userRepository->find($id);

		return view('pages.user.edit', compact('user'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, UserRequest $request) {
        if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.edit'))) return redirect()->route('home');
        Log::info('update: '.$id, $request->all());

        //dd(__METHOD__.'('.__LINE__.')', compact('id','request'));
		$this->userRepository->update($id, $request->all());

		return redirect()->route('user.index');
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
        if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.delete'))) return redirect()->route('home');
        Log::info("delete: ".$id);
        $user = $this->userRepository->find($id);
        $deleted = false;

        if(isset($user)) {
            /*
             * In the case of a User delete request
             * 1. make sure there are no Roles for this User
             * ok to delete
             */
            $roles = $this->roleUserRepository->filterOn(['user_id' => $id]);
            Log::debug('Roles: '.(isset($roles) ? count($roles) : 'none' ));
            if(isset($roles) and count($roles) > 0) {
                $children = Lang::get('labels.titles.Role');
                $model = Lang::get('labels.titles.User');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return redirect()->back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__."(".__LINE__.")",compact('id','user','roles'));

            Log::debug(Auth::user()->name.' - delete: '.$id);
            $deleted = $user->delete();
        }

        Log::info('deleted: '.($deleted ? 'yes' : 'no'));
        return redirect()->route('user.index');
    }

}
