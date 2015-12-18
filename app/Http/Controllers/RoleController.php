<?php namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\View;
use vital40\Repositories\RoleRepositoryInterface;
use \Entrust;
use \Request;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\RoleRepositoryInterface
	 */ 
	protected $roleRepository;

	/**
	 * Constructor requires Role Repository
	 */ 
	public function __construct(RoleRepositoryInterface $roleRepository) {
		$this->roleRepository = $roleRepository;
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

        $role = Request::all();
        if(count($role) == 0) {
            // lets provide a default filter
            //$role['..'] = ..;
        }

		// using an implementation of the Role Repository Interface
		$roles = $this->roleRepository->paginate($role);

		// Using the view(..) helper function
		return view('pages.role.index', compact('role', 'roles'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
        }

		$role = Request::all();

		// using an implementation of the Role Repository Interface
		$roles = $this->roleRepository->paginate($role);

		// populate a View
		return View::make('pages.role.index', compact('role', 'roles'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
        }

		// using an implementation of the Role Repository Interface
		$role = $this->roleRepository->find($id);
		//dd($role);

		return view('pages.role.show', compact('role'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot role.create, redirect -> home
            if (Entrust::can('role.create') == false) return redirect()->route('home');
        }

		return view('pages.role.create');
	}

	/**
	 * Store a new resource
	 * @param RoleRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(RoleRequest $request) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot role.create, redirect -> home
            if (Entrust::can('role.create') == false) return redirect()->route('home');
        }

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Role object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$role = $this->roleRepository->create($request->all());

		// to see our $role, we could Dump and Die here
		// dd($role);

		return redirect()->route('role.show', ['id' => $role->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot role.edit, redirect -> home
            if (Entrust::can('role.edit') == false) return redirect()->route('home');
        }

		// using an implementation of the Role Repository Interface
		$role = $this->roleRepository->find($id);

		return view('pages.role.edit', compact('role'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, RoleRequest $request) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot role.edit, redirect -> home
            if (Entrust::can('role.edit') == false) return redirect()->route('home');
        }

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();

		$this->roleRepository->update($id, $input);

		return redirect()->route('role.index');
	}

}
