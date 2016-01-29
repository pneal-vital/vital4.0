<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use vital40\Repositories\PermissionRepositoryInterface;
use vital40\Repositories\PermissionRoleRepositoryInterface;
use vital40\Repositories\RoleRepositoryInterface;
use \Auth;
use \Entrust;
use \Log;
use \Request;

/**
 * Class RolePermissionsController
 * @package App\Http\Controllers
 */
class RolePermissionsController extends Controller {

	use SaveRequest;

    /**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\PermissionRoleRepositoryInterface
	 */
	protected $permissionRepository;
    protected $permissionRoleRepository;
    protected $roleRepository;

	/**
	 * Constructor requires Role Repository
	 */ 
	public function __construct(
        PermissionRepositoryInterface $permissionRepository,
        PermissionRoleRepositoryInterface $permissionRoleRepository,
        RoleRepositoryInterface $roleRepository
    ) {
		$this->permissionRepository = $permissionRepository;
		$this->permissionRoleRepository = $permissionRoleRepository;
		$this->roleRepository = $roleRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
		// Entrust::hasRole('role-name');
		// Entrust::can('permission-name');
        if(Entrust::hasRole(['support', 'admin']) == false) return redirect()->route('home');

		$rolePermission = $this->getRequest('RolePermission');

		// using an implementation of the Role Repository Interface
		$rolePermissions = $this->permissionRoleRepository->filterOn($rolePermission, 0);
		//dd(__METHOD__.'('.__LINE__.')', compact('rolePermission', 'rolePermissions'));

		// Using the view(..) helper function
		return view('pages.rolePermissions.index', compact('rolePermission', 'rolePermissions'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::hasRole(['support', 'admin']) == false) return redirect()->route('home');

		$rolePermission = $this->getRequest('RolePermission');

		// using an implementation of the Role Repository Interface
		$rolePermissions = $this->permissionRoleRepository->filterOn($rolePermission, 0);
		//dd(__METHOD__.'('.__LINE__.')', compact('rolePermission', 'rolePermissions'));

		// populate a View
		return View::make('pages.rolePermissions.index', compact('rolePermission', 'rolePermissions'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		if(Entrust::hasRole(['support', 'admin']) == false) return redirect()->route('home');

		// using an implementation of the Role Repository Interface
		$rolePermission = $this->permissionRoleRepository->find($id);
		//dd(__METHOD__.'('.__LINE__.')', compact('rolePermission'));

		return view('pages.rolePermissions.show', compact('rolePermission'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot rolePermissions.create, redirect -> home
            if (Entrust::can('rolePermissions.create') == false) return redirect()->route('home');
        }
        Log::debug('Auth::user(): '.Auth::user()->name);

		return view('pages.rolePermissions.create');
	}

	/**
	 * Store a new resource
	 * @param RoleRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(RoleRequest $request) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot rolePermissions.create, redirect -> home
            if (Entrust::can('rolePermissions.create') == false) return redirect()->route('home');
        }
        Log::debug('Auth::user(): '.Auth::user()->name);

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Role object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$rolePermission = $this->permissionRoleRepository->create($request->all());

		// to see our $role, we could Dump and Die here
		//dd(__METHOD__.'('.__LINE__.')', compact('request','rolePermission'));

		return redirect()->route('rolePermissions.show', ['id' => $rolePermission->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
        if(Entrust::hasRole('support') == false) {
            if(Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot rolePermissions.edit, redirect -> home
            if(Entrust::can('rolePermissions.edit') == false) return redirect()->route('home');
        }
        Log::debug(Auth::user()->name.' - id: '.$id);

        $rolePermission = Request::all();
        if(isset($rolePermission['role_id']) == false) {
            $rolePermission['role_id'] = $id;
        }

		// using an implementation of the Role Repository Interface
		$rolePermissions = $this->permissionRoleRepository->filterOn(['role_id' => $rolePermission['role_id']], 0);

        // ok, here we want the $rolePermissions sorted on permission->name
        $permissions = [];
        foreach($this->permissionRepository->getAll(0) as $p) {
            $permissions[strtolower($p->name)] = $p;
            $permissions[strtolower($p->name)]['checked'] = false;
        }
        ksort($permissions);

        // turn on (checked) permissions already set
        foreach($rolePermissions as $rp) {
            $permissions[strtolower($rp->permission->name)]['checked'] = true;
        }

        // disabled any permission, when there is a .view of the permission and .view is not checked
        foreach($permissions as $p) {
            $p['disabled'] = false;
            if(strpos($p->name,'.') > 0 && strpos($p->name,'.view') == 0) {
                $viewName = substr(strtolower($p->name),0,strpos($p->name,'.')) . '.view';
                if(isset($permissions[$viewName]) && $permissions[$viewName]->checked == false) {
                    $p['disabled'] = true;
                }
            }
        }

        //dd(__METHOD__.'('.__LINE__.')', compact('id','role_id','rolePermission','rolePermissions','permissions'));

		return view('pages.rolePermissions.edit', compact('rolePermission','rolePermissions','permissions'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id) {
        if(Entrust::hasRole('support') == false) {
            if(Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot rolePermissions.edit, redirect -> home
            if(Entrust::can('rolePermissions.edit') == false) return redirect()->route('home');
        }

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = Request::all();

        if(isset($input['role_id']) && $input['role_id'] != $id) {
            return $this->edit($input['role_id']);
        }
        if(isset($input['btn_Cancel'])) {
            return $this->edit($input['role_id']);
        }
        Log::debug(Auth::user()->name.' - id: '.$id, $input);

        // using an implementation of the Role Repository Interface
        $rolePermissions = $this->permissionRoleRepository->filterOn(['role_id' => $input['role_id']], 0);

        // delete any that have been unchecked
        $rpsByName = [];
        foreach($rolePermissions as $rp) {
            $cbName = "cb_" . preg_replace("/\./","_",$rp->permission->name);
            if(isset($input[$cbName]) == false) {
                $rp->delete();
            }
            $rpsByName[$rp->permission->name] = $rp;
        }

        // add any that have been checked
        foreach($this->permissionRepository->getAll(0) as $p) {
            $cbName = "cb_" . preg_replace("/\./","_",$p->name);
            if(isset($input[$cbName]) && isset($rpsByName[$p->name]) == false) {
                $this->permissionRoleRepository->create(['role_id' => $input['role_id'], 'permission_id' => $p->id]);
            }
        }

        //dd(__METHOD__.'('.__LINE__.')', compact('id','input','cbName','rpsByName','rolePermissions'));

		return redirect()->route('rolePermissions.index');
	}

}
