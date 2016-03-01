<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use vital40\Repositories\RoleRepositoryInterface;
use vital40\Repositories\RoleUserRepositoryInterface;
use vital40\Repositories\UserRepositoryInterface;
use \Auth;
use \Entrust;
use \Log;
use \Request;

/**
 * Class UserRolesController
 * @package App\Http\Controllers
 */
class UserRolesController extends Controller {

	use SaveRequest;

    /**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\RoleUserRepositoryInterface
	 */
	protected $roleRepository;
    protected $roleUserRepository;
    protected $userRepository;

	/**
	 * Constructor requires Role Repository
	 */ 
	public function __construct(
          RoleRepositoryInterface $roleRepository
        , RoleUserRepositoryInterface $roleUserRepository
        , UserRepositoryInterface $userRepository
    ) {
		$this->roleRepository = $roleRepository;
		$this->roleUserRepository = $roleUserRepository;
		$this->userRepository = $userRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
		// Entrust::hasRole('role-name');
		// Entrust::can('permission-name');
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.view'))) return redirect()->route('home');

		$userRole = $this->getRequest('UserRole');

		// Because we are not selecting user_id fro a set provided list (like we can with role_id), we can see if the name points to one user
        $users = $this->userRepository->filterOn($userRole, 2);
        if(count($users) == 1) {
            $userRole['user_id'] = $users[0]->id;
        }

		// using an implementation of the Role Repository Interface
		$userRoles = $this->roleUserRepository->paginate($userRole);
		//dd(__METHOD__.'('.__LINE__.')', compact('userRole', 'userRoles'));

		// Using the view(..) helper function
		return view('pages.userRoles.index', compact('userRole', 'userRoles'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.view'))) return redirect()->route('home');

		$userRole = $this->getRequest('UserRole');


        // Because we are not selecting user_id fro a set provided list (like we can with role_id), we can see if the name points to one user
        $users = $this->userRepository->filterOn($userRole, 2);
        if(count($users) == 1) {
            $userRole['user_id'] = $users[0]->id;
        }

        // using an implementation of the Role Repository Interface
		$userRoles = $this->roleUserRepository->paginate($userRole);
		//dd(__METHOD__.'('.__LINE__.')', compact('userRole', 'userRoles'));

		// populate a View
		return View::make('pages.userRoles.index', compact('userRole', 'userRoles'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.view'))) return redirect()->route('home');

		// using an implementation of the Role Repository Interface
		$userRole = $this->roleUserRepository->find($id);
		//dd(__METHOD__.'('.__LINE__.')', compact('userRole'));

		return view('pages.userRoles.show', compact('userRole'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.create'))) return redirect()->route('home');
        Log::debug('create');

		return view('pages.userRoles.create');
	}

	/**
	 * Store a new resource
	 * @param RoleRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(RoleRequest $request) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.create'))) return redirect()->route('home');
		Log::info('save new:', $request->all());

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Role object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$userRole = $this->roleUserRepository->create($request->all());

		// to see our $role, we could Dump and Die here
		//dd(__METHOD__.'('.__LINE__.')', compact('request','userRole'));
		return redirect()->route('userRoles.show', ['id' => $userRole->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.edit'))) return redirect()->route('home');
		Log::debug('edit: '.$id);

        $userRole = Request::all();
        if(isset($userRole['user_id']) == false) {
            $userRole['user_id'] = $id;
        }

		// using an implementation of the Role Repository Interface
		$userRoles = $this->roleUserRepository->filterOn(['user_id' => $userRole['user_id']], 0);
		$user = $this->userRepository->find($userRole['user_id']);

        // ok, here we want the $userRoles sorted on role->name
        $rNames = [];
        foreach($this->roleRepository->getAll(0) as $r) {
            $rNames[strtolower($r->name)] = $r;
            $rNames[strtolower($r->name)]['checked'] = false;
        }
        ksort($rNames);

        // turn on (checked) rNames already set
        foreach($userRoles as $ur) {
            $rNames[strtolower($ur->role->name)]['checked'] = true;
        }

        // disabled any role, when there is a .view of the role and .view is not checked
        foreach($rNames as $r) {
            $r['disabled'] = false;
            if(strpos($r->name,'.') > 0 && strpos($r->name,'.view') == 0) {
                $viewName = substr(strtolower($r->name),0,strpos($r->name,'.')) . '.view';
                if(isset($rNames[$viewName]) && $rNames[$viewName]->checked == false) {
                    $r['disabled'] = true;
                }
            }
        }

        //dd(__METHOD__.'('.__LINE__.')', compact('id','user_id','userRole','user','userRoles','rNames'));
		return view('pages.userRoles.edit', compact('userRole','user','userRoles','rNames'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('user.edit'))) return redirect()->route('home');

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = Request::all();

        if(isset($input['user_id']) == false) $input['user_id'] = $id;
        if(isset($input['user_id']) && $input['user_id'] != $id) {
            return $this->edit($input['user_id']);
        }
        if(isset($input['btn_Cancel'])) {
            return $this->edit($input['user_id']);
        }
        Log::info('update: '.$id, $input);
        //dd(__METHOD__.'('.__LINE__.')', compact('id','input'));

        // using an implementation of the Role Repository Interface
        $userRoles = $this->roleUserRepository->filterOn(['user_id' => $input['user_id']], 0);

        // delete any that have been unchecked
        $ursByName = [];
        foreach($userRoles as $ur) {
            $cbName = "cb_" . preg_replace("/\./","_",$ur->role->name);
            if(isset($input[$cbName]) == false) {
                $ur->delete();
            }
            $ursByName[$ur->role->name] = $ur;
        }

        // add any that have been checked
        foreach($this->roleRepository->getAll(0) as $p) {
            $cbName = "cb_" . preg_replace("/\./","_",$p->name);
            if(isset($input[$cbName]) && isset($ursByName[$p->name]) == false) {
                $this->roleUserRepository->create(['user_id' => $input['user_id'], 'role_id' => $p->id]);
            }
        }

        //dd(__METHOD__.'('.__LINE__.')', compact('id','input','cbName','ursByName','userRoles'));
		return redirect()->route('userRoles.index');
	}

}
