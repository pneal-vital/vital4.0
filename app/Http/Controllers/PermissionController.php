<?php namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Support\Facades\View;
use vital40\Repositories\PermissionRepositoryInterface;
use \Entrust;
use \Request;


/**
 * Class PermissionController
 * @package App\Http\Controllers
 */
class PermissionController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\PermissionRepositoryInterface
	 */ 
	protected $permissionRepository;

	/**
	 * Constructor requires Permission Repository
	 */ 
	public function __construct(PermissionRepositoryInterface $permissionRepository) {
		$this->permissionRepository = $permissionRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        if(Entrust::hasRole('support') == false) {
            // Entrust::hasRole('role-name');
            // Entrust::can('permission-name');
            if(Entrust::hasRole('admin') == false) return redirect()->route('home');
        }

        $permission = Request::all();
        if(count($permission) == 0) {
            // lets provide a default filter
            //$permission['..'] = ..;
        }

		// using an implementation of the Permission Repository Interface
		$permissions = $this->permissionRepository->paginate($permission);

		// Using the view(..) helper function
		return view('pages.permission.index', compact('permission', 'permissions'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
        }

		$permission = Request::all();

		// using an implementation of the Permission Repository Interface
		$permissions = $this->permissionRepository->paginate($permission);

		// populate a View
		return View::make('pages.permission.index', compact('permission', 'permissions'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
        }

		// using an implementation of the Permission Repository Interface
		$permission = $this->permissionRepository->find($id);
		//dd($permission);

		return view('pages.permission.show', compact('permission'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot permission.create, redirect -> home
            if (Entrust::can('permission.create') == false) return redirect()->route('home');
        }

		return view('pages.permission.create');
	}

	/**
	 * Store a new resource
	 * @param PermissionRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(PermissionRequest $request) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot permission.create, redirect -> home
            if (Entrust::can('permission.create') == false) return redirect()->route('home');
        }

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Permission object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$permission = $this->permissionRepository->create($request->all());

		// to see our $permission, we could Dump and Die here
		// dd($permission);

		return redirect()->route('permission.show', ['id' => $permission->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot permission.edit, redirect -> home
            if (Entrust::can('permission.edit') == false) return redirect()->route('home');
        }

		// using an implementation of the Permission Repository Interface
		$permission = $this->permissionRepository->find($id);

		return view('pages.permission.edit', compact('permission'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, PermissionRequest $request) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot permission.edit, redirect -> home
            if (Entrust::can('permission.edit') == false) return redirect()->route('home');
        }

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();

		$this->permissionRepository->update($id, $input);

		return redirect()->route('permission.index');
	}

}
