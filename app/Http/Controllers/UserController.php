<?php namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\View;
use vital40\Repositories\UserRepositoryInterface;
use \Entrust;
use \Request;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\UserRepositoryInterface
	 */ 
	protected $userRepository;

	/**
	 * Constructor requires User Repository
	 */ 
	public function __construct(UserRepositoryInterface $userRepository) {
		$this->userRepository = $userRepository;
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

        $user = Request::all();
        if(count($user) == 0) {
            // lets provide a default filter
            //$user['..'] = ..;
        }

		// using an implementation of the User Repository Interface
		$users = $this->userRepository->paginate($user);

		// Using the view(..) helper function
		return view('pages.user.index', compact('user', 'users'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
        }

		$user = Request::all();

		// using an implementation of the User Repository Interface
		$users = $this->userRepository->paginate($user);

		// populate a View
		return View::make('pages.user.index', compact('user', 'users'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
        }

		// using an implementation of the User Repository Interface
		$user = $this->userRepository->find($id);
		//dd($user);

		return view('pages.user.show', compact('user'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot user.create, redirect -> home
            if (Entrust::can('user.create') == false) return redirect()->route('home');
        }

		return view('pages.user.create');
	}

	/**
	 * Store a new resource
	 * @param UserRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(UserRequest $request) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot user.create, redirect -> home
            if (Entrust::can('user.create') == false) return redirect()->route('home');
        }

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new User object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$user = $this->userRepository->create($request->all());

		// to see our $user, we could Dump and Die here
		// dd($user);

		return redirect()->route('user.show', ['id' => $user->objectID]);
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot user.edit, redirect -> home
            if (Entrust::can('user.edit') == false) return redirect()->route('home');
        }

		// using an implementation of the User Repository Interface
		$user = $this->userRepository->find($id);

		return view('pages.user.edit', compact('user'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, UserRequest $request) {
        if(Entrust::hasRole('support') == false) {
            if (Entrust::hasRole('admin') == false) return redirect()->route('home');
            // if guest or cannot user.edit, redirect -> home
            if (Entrust::can('user.edit') == false) return redirect()->route('home');
        }

		/*
		 * Here we can apply any business logic required,
		 * then change $request->all() to results.
		 */
		$input = $request->all();

		$this->userRepository->update($id, $input);

		return redirect()->route('user.index');
	}

}
