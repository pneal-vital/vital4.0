<?php namespace App\Http\Controllers\vital40;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SaveRequest;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\View;
use vital3\Repositories\ClientRepositoryInterface;
use vital3\Repositories\InventoryRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use \Auth;
use \Entrust;
use \Lang;
use \Log;
use \Redirect;
use \Request;
use \Session;

/**
 * Class ArticleController
 * @package App\Http\Controllers
 */
class ArticleController extends Controller {

    use SaveRequest;

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\ArticleRepositoryInterface
	 */
	protected $articleRepository;
	protected $clientRepository;
    protected $inventoryRepository;
    protected $purchaseOrderDetailRepository;
    protected $upcRepository;

    /**
	 * Constructor requires Article Repository
	 */
	public function __construct(
          ArticleRepositoryInterface $articleRepository
        , ClientRepositoryInterface $clientRepository
        , InventoryRepositoryInterface $inventoryRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , UPCRepositoryInterface $upcRepository
    ) {
		$this->articleRepository = $articleRepository;
		$this->clientRepository = $clientRepository;
		$this->inventoryRepository = $inventoryRepository;
		$this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
		$this->upcRepository = $upcRepository;
    }

    protected function defaultRequest() {
        $defaultRequest = [];
        // lets provide a default filter
        $client = $this->clientRepository->filterOn(['Client_Name' => 'LCL'], 1);
        $defaultRequest['Client_Code'] = $client->objectID;
        return $defaultRequest;
    }

    /**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $article = $this->getRequest('Article');

		// using an implementation of the Article Repository Interface
		$articles = $this->articleRepository->paginate($article);

		// Using the view(..) helper function
		return view('pages.article.index', compact('article', 'articles'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        $article = $this->getRequest('Article');

		// using an implementation of the Article Repository Interface
		$articles = $this->articleRepository->paginate($article);

		// populate a View
        //dd(__METHOD__."(".__LINE__.")",compact('article', 'articles'));
		return View::make('pages.article.index', compact('article', 'articles'));
	}

	/**
	 * Display a specific resource
	 */
	public function show($id) {
		// using an implementation of the Article Repository Interface
		$article = $this->articleRepository->find($id);
		$upcs = $this->upcRepository->paginateArticleUPCs($id);
		//dd(__METHOD__."(".__LINE__.")",compact('article', 'upcs'));

		return view('pages.article.show', compact('article', 'upcs'));
	}

	/**
	 * Create a new resource.
	 */
	public function create() {
		// if guest or cannot article.create, redirect -> home
		if(Entrust::can('article.create') == false) return redirect()->route('home');

		return view('pages.article.create');
	}

	/**
	 * Store a new resource
	 * @param ArticleRequest $request - do some validation before this store(..) function is called
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function store(ArticleRequest $request) {
		// if guest or cannot article.create, redirect -> home
		if(Entrust::can('article.create') == false) return redirect()->route('home');

		/*
		 *  retrieve all the request form field values
		 *  and pass them into create to mass update the new Article object
		 *  Can replace Request::all() in the call to create, because we added validation.
		 */
		$article = $this->articleRepository->create($request->all());

		return redirect()->route('article.index');
	}

	/**
	 * Retrieve an existing resource for edit
	 */
	public function edit($id) {
		// if guest or cannot article.edit, redirect -> home
		if(Entrust::can('article.edit') == false) return redirect()->route('home');

		// using an implementation of the Article Repository Interface
		$article = $this->articleRepository->find($id);

		return view('pages.article.edit', compact('article'));
	}

	/**
	 * Apply the updates to our resource
	 */
	public function update($id, ArticleRequest $request) {
		// if guest or cannot article.edit, redirect -> home
		if(Entrust::can('article.edit') == false) return redirect()->route('home');

        // using an implementation of the Article Repository Interface
		$this->articleRepository->update($id, $request->all());

		return redirect()->route('article.index');
	}

    /**
     * Implement destroy($id)
     */
    public function destroy($id) {
        Log::debug(__METHOD__."(".__LINE__."):  id: ".$id);
        $article = $this->articleRepository->find($id);
        $deleted = false;

        if(isset($article)) {
            /*
             * In the case of an Article delete request
             * 1. make sure there are no UPCs for this Article
             * 2. make sure there are no Inventory records that reference this Article
             * 3. make sure there are no Inbound_Order_Detail lines that reference this Article
             * ok to delete
             */
            $upcs = $this->upcRepository->getArticleUPCs($id);
            Log::debug(__METHOD__."(".__LINE__."):  UPCs: ".(isset($upcs) ? count($upcs) : 'none' ));
            if(isset($upcs) and count($upcs) > 0) {
                $children = Lang::get('labels.titles.UPCs');
                $model = Lang::get('labels.titles.Article');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            $inventories = $this->inventoryRepository->filterOn(['THOU.articleID' => $id]);
            Log::debug(__METHOD__."(".__LINE__."):  Inventories: ".(isset($inventories) ? count($inventories) : 'none' ));
            if(isset($inventories) and count($inventories) > 0) {
                $children = Lang::get('labels.titles.Inventories');
                $model = Lang::get('labels.titles.Article');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            $pods = $this->purchaseOrderDetailRepository->filterOn(['SKU' => $id]);
            Log::debug(__METHOD__."(".__LINE__."):  PODs: ".(isset($pods) ? count($pods) : 'none' ));
            if(isset($pods) and count($pods) > 0) {
                $children = Lang::get('labels.titles.PurchaseOrderDetails');
                $model = Lang::get('labels.titles.Article');
                $errors = [[Lang::get('internal.errors.deleteHasChildren', ['Model' => $model, 'Children' => $children])]];
                return Redirect::back()->withErrors($errors)->withInput();
            }
            //dd(__METHOD__."(".__LINE__.")",compact('id','article','upcs','inventories','pods'));

            Log::debug(__METHOD__."(".__LINE__."):  delete: ".$id);
            $deleted = $this->articleRepository->delete($id);
        }

        Log::debug(__METHOD__."(".__LINE__."):  deleted: ".($deleted ? 'yes' : 'no'));
        return $this->index();
    }

}
