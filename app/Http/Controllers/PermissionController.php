<?php namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Support\Facades\View;
use vital40\Repositories\PermissionRepositoryInterface;
use vital40\Repositories\PermissionRoleRepositoryInterface;
use \Entrust;
use \Lang;
use \Log;
use \Redirect;

/**
 * Class PermissionController
 * @package App\Http\Controllers
 */
class PermissionController extends Controller implements PermissionControllerInterface {

	use SaveRequest;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\PermissionRepositoryInterface
	 */ 
	protected $permissionRepository;
	protected $permissionRoleRepository;

	/**
	 * Constructor requires Permission Repository
	 */ 
	public function __construct(
		  PermissionRepositoryInterface $permissionRepository
		, PermissionRoleRepositoryInterface $permissionRoleRepository
	) {
		$this->permissionRepository = $permissionRepository;
		$this->permissionRoleRepository = $permissionRoleRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
		// Entrust::hasRole('role-name');
		// Entrust::can('permission-name');
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('permission.view'))) return redirect()->route('home');

		$permission = $this->getRequest('Permission');

		// using an implementation of the Permission Repository Interface
		$permissions = $this->permissionRepository->paginate($permission);

		// Using the view(..) helper function
		return view('pages.permission.index', compact('permission', 'permissions'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('permission.view'))) return redirect()->route('home');

		$permission = $this->getRequest('Permission');

		// using an implementation of the Permission Repository Interface
		$permissions = $this->permissionRepository->paginate($permission);

		// populate a View
		return View::make('pages.permission.index', compact('permission', 'permissions'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('permission.view'))) return redirect()->route('home');

		// using an implementation of the Permission Repository Interface
		$permission = $this->permissionRepository->find($id);

		//dd(__METHOD__.'('.__LINE__.')',compact('permission'));
		return view('pages.permission.show', compact('permission'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('permission.create'))) return redirect()->route('home');
        Log::debug('create');

		return view('pages.permission.create');
	}

	/**
	 * Store a new resource
	 * @param PermissionRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(PermissionRequest $request) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('permission.create'))) return redirect()->route('home');
        Log::info('save new:', $request->all());

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Permission object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$permission = $this->permissionRepository->create($request->all());

		// to see our $permission, we could Dump and Die here
		// dd(__METHOD__.'('.__LINE__.')',compact('permission'));
		return redirect()->route('permission.show', ['id' => $permission->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('permission.edit'))) return redirect()->route('home');
        Log::debug('edit: '.$id);

		// using an implementation of the Permission Repository Interface
		$permission = $this->permissionRepository->find($id);

		return view('pages.permission.edit', compact('permission'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, PermissionRequest $request) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('permission.edit'))) return redirect()->route('home');
        Log::info('update: '.$id,$request->all());

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();

		$this->permissionRepository->update($id, $input);

		return redirect()->route('permission.index');
	}

	/**
	 * Retrieve a list of the resource.
	 */
	public function lists($columnName) {

		// using an implementation of the UOM Repository Interface
		$permissions = $this->permissionRepository->lists(100);

		// pull out the requested columnName
		$result = array();
		foreach($permissions as $permission) {
			$result[ $permission['id'] ] = $permission[$columnName];
		}
		Log::debug('before asort($result):'.$result);
		asort($result);
		Log::debug(' after asort(_result):'.$result);
		//dd(__METHOD__.'('.__LINE__.')',compact('result'));

		// return an array of results
		return $result;
	}

	/**
	 * Retrieve a translation of the resource.
	 */
	public function translate($columnName) {

		// using an implementation of the UOM Repository Interface
		$permissions = $this->permissionRepository->lists(0);

		// pull out the requested columnName
		$result = array();
		foreach($permissions as $permission) {
			$result[ $permission['id'] ] = $permission[$columnName];
		}
		//dd(__METHOD__.'('.__LINE__.')',compact('result'));

		// return an array of results
		return $result;
	}

	/**
	 * Implement destroy($id)
	 */
	public function destroy($id) {
		if(!(Entrust::hasRole(['support', 'admin']) || Entrust::can('permission.delete'))) return redirect()->route('home');
		Log::info("delete: ".$id);
		$permission = $this->permissionRepository->find($id);
		$deleted = false;

		if(isset($permission)) {
			/*
             * In the case of a Permission delete request
             * 1. make sure there are no Roles connected to this Permission
             * ok to delete
             */
			$roles = $this->permissionRoleRepository->filterOn(['permission_id' => $id]);
			Log::debug('Roles: '.(isset($roles) ? count($roles) : 'none' ));
			if(isset($roles) and count($roles) > 0) {
				$parent = Lang::get('labels.titles.Permission');
				$model = Lang::get('labels.titles.Role');
				$errors = [[Lang::get('internal.errors.deleteHasParent', ['Model' => $model, 'Parent' => $parent])]];
				return Redirect::back()->withErrors($errors)->withInput();
			}
			//dd(__METHOD__."(".__LINE__.")",compact('id','permission','roles'));

			Log::debug('delete: '.$id);
			$deleted = $permission->delete();
		}

		Log::info('deleted: '.($deleted ? 'yes' : 'no'));
        return redirect()->route('permission.index');
	}

}
