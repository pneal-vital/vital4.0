<?php namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\View;
use vital40\Repositories\PermissionRoleRepositoryInterface;
use vital40\Repositories\RoleRepositoryInterface;
use vital40\Repositories\RoleUserRepositoryInterface;
use \Entrust;
use \Lang;
use \Log;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller implements RoleControllerInterface {

    use SaveRequest;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\RoleRepositoryInterface
	 */ 
	protected $permissionRoleRepository;
	protected $roleRepository;
	protected $roleUserRepository;

	/**
	 * Constructor requires Role Repository
	 */ 
	public function __construct(
          PermissionRoleRepositoryInterface $permissionRoleRepository
        , RoleRepositoryInterface $roleRepository
        , RoleUserRepositoryInterface $roleUserRepository
    ) {
		$this->permissionRoleRepository = $permissionRoleRepository;
		$this->roleRepository = $roleRepository;
		$this->roleUserRepository = $roleUserRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        // Entrust::hasRole('role-name');
        // Entrust::can('permission-name');
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('role.view'))) return redirect()->route('home');

        $role = $this->getRequest('Role');

		// using an implementation of the Role Repository Interface
		$roles = $this->roleRepository->paginate($role);

		// Using the view(..) helper function
		return view('pages.role.index', compact('role', 'roles'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('role.view'))) return redirect()->route('home');

        $role = $this->getRequest('Role');

		// using an implementation of the Role Repository Interface
		$roles = $this->roleRepository->paginate($role);

		// populate a View
		return View::make('pages.role.index', compact('role', 'roles'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('role.view'))) return redirect()->route('home');

		// using an implementation of the Role Repository Interface
		$role = $this->roleRepository->find($id);
        $rolePermissions = $this->permissionRoleRepository->filterOn([ 'role_id' => $id ], 0);
        $permissions = array();
        foreach($rolePermissions as $rolePermission) {
            $permissions[] = $rolePermission->permission;
        }

		//dd(__METHOD__.'('.__LINE__.')',compact('id','role','rolePermissions','permissions'));
		return view('pages.role.show', compact('role','permissions'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('role.create'))) return redirect()->route('home');
		Log::debug('create');

		return view('pages.role.create');
	}

	/**
	 * Store a new resource
	 * @param RoleRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(RoleRequest $request) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('role.create'))) return redirect()->route('home');
		Log::info('save new:', $request->all());

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Role object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$role = $this->roleRepository->create($request->all());

		// to see our $role, we could Dump and Die here
		// dd(__METHOD__.'('.__LINE__.')',compact('request','role'));
		return redirect()->route('role.show', ['id' => $role->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('role.edit'))) return redirect()->route('home');
		Log::debug('edit: '.$id);

		// using an implementation of the Role Repository Interface
		$role = $this->roleRepository->find($id);

		return view('pages.role.edit', compact('role'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, RoleRequest $request) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('role.edit'))) return redirect()->route('home');
		Log::info('update: '.$id, $request->all());

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();

		$this->roleRepository->update($id, $input);

		return redirect()->route('role.index');
	}

	/**
	 * Retrieve a list of the resource.
	 */
	public function lists($columnName) {

		// using an implementation of the UOM Repository Interface
		$roles = $this->roleRepository->lists(100);

		// pull out the requested columnName
		$result = array();
		foreach($roles as $role) {
			$result[ $role['id'] ] = $role[$columnName];
		}
        Log::debug('before asort($result):'.$result);
        asort($result);
        Log::debug(' after asort(_result):'.$result);
        //dd(__METHOD__.'('.__LINE__.')',compact('roles','result'));

		// return an array of results
		return $result;
	}

	/**
	 * Retrieve a translation of the resource.
	 */
	public function translate($columnName) {

		// using an implementation of the UOM Repository Interface
		$roles = $this->roleRepository->lists(0);

		// pull out the requested columnName
		$result = array();
		foreach($roles as $role) {
			$result[ $role['id'] ] = $role[$columnName];
		}
        //dd(__METHOD__.'('.__LINE__.')',compact('result'));

		// return an array of results
		return $result;
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('role.delete'))) return redirect()->route('home');
		Log::info("delete: ".$id);
        $role = $this->roleRepository->find($id);
        $deleted = false;

        if(isset($role)) {
            /*
             * In the case of a Role delete request
             * 1. make sure there are no Permissions for this Role
             * 2. make sure there are no Users with this Role
             * ok to delete
             */
            $permissions = $this->permissionRoleRepository->filterOn(['role_id' => $id]);
            Log::debug('Permissions: '.(isset($permissions) ? count($permissions) : 'none' ));
            if(isset($permissions) and count($permissions) > 0) {
                $children = Lang::get('labels.titles.Permission');
                $model = Lang::get('labels.titles.Role');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return redirect()->back()->withErrors($errors)->withInput();
            }
            $users = $this->roleUserRepository->filterOn(['role_id' => $id]);
            Log::debug('Users: '.(isset($users) ? count($users) : 'none' ));
            if(isset($users) and count($users) > 0) {
                $parent = Lang::get('labels.titles.User');
                $model = Lang::get('labels.titles.Role');
                $errors = [[Lang::get('internal.errors.deleteHasParent', ['Model' => $model, 'Parent' => $parent])]];
                return redirect()->back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__."(".__LINE__.")",compact('id','role','permissions','users'));

            Log::debug('delete: '.$id);
            $deleted = $role->delete();
        }

        Log::info('deleted: '.($deleted ? 'yes' : 'no'));
        return redirect()->route('role.index');
    }

}
