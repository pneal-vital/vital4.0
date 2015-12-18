<?php namespace App\Http\Controllers\Receive;

// use a Repository Interface
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;
use vital3\Repositories\LocationRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\PurchaseOrderRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use \Auth;
use \Config;
use \Entrust;
use \Lang;
use \Log;
use \ReceivePO;
use \Request;

/**
 * Class ReceivePOController
 * @package App\Http\Controllers
 */
class ReceivePOController extends Controller implements ReceivePOControllerInterface {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\PurchaseOrderRepositoryInterface
	 */
    protected $articleRepository;
    protected $locationRepository;
    protected $purchaseOrderDetailRepository;
	protected $purchaseOrderRepository;
	protected $userActivityRepository;

	/**
	 * Constructor requires purchaseOrder Repository
	 */
	public function __construct(
          ArticleRepositoryInterface $articleRepository
        , LocationRepositoryInterface $locationRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , PurchaseOrderRepositoryInterface $purchaseOrderRepository
        , UserActivityRepositoryInterface $userActivityRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->locationRepository = $locationRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
		$this->purchaseOrderRepository = $purchaseOrderRepository;
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
        if(isset($location) == false) {
            return redirect()->route('receiveLocation.index');
        }

        // Do we know Purchase Order?
        $filter = [
            'classID'   => Config::get('constants.userActivity.classID.ReceivePO'),
            'User_Name' => Auth::user()->name,
        ];
        $userActivity = $this->userActivityRepository->filterOn($filter, $limit = 1);
        if(isset($userActivity)) {
            $purchaseOrder = $this->purchaseOrderRepository->find($userActivity->id);
        }
        if(isset($purchaseOrder)) {
            return $this->show($purchaseOrder->objectID);
        }

        $purchaseOrder = Request::all();
        if(count($purchaseOrder) == 0) {
            // lets provide a default filter
            $purchaseOrder = [
                'Expected' => Carbon::today()->format('Y-m'),
                'Status' => Config::get('constants.purchaseOrder.status.open'),
            ];
        }
		// using an implementation of the purchaseOrder Repository Interface
		$purchaseOrders = $this->purchaseOrderRepository->paginate($purchaseOrder);

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status'), 'OPEN' => Lang::get('lists.purchaseOrder.status.OPEN'), 'REC' => Lang::get('lists.purchaseOrder.status.REC')];

		// Using the view(..) helper function
		return view('pages.receivePO.index', compact('purchaseOrder', 'purchaseOrders', 'statuses'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        $purchaseOrder = Request::all();
		// using an implementation of the purchaseOrder Repository Interface
		$purchaseOrders = $this->purchaseOrderRepository->paginate($purchaseOrder);

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status'), 'OPEN' => Lang::get('lists.purchaseOrder.status.OPEN'), 'REC' => Lang::get('lists.purchaseOrder.status.REC')];

		// populate a View
		return View::make('pages.receivePO.index', compact('purchaseOrder', 'purchaseOrders', 'statuses'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        // using an implementation of the purchaseOrder Repository Interface
		$purchaseOrder = $this->purchaseOrderRepository->find($id);

		// calling into business logic
		ReceivePO::associate($purchaseOrder);

		// Merge a list Purchase Order Details with their Articles
		$receiveArticles = $this->mergePODetailWithArticle($purchaseOrder->objectID);
        //dd(__METHOD__."(".__LINE__.")",compact('receiveArticles'));

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status')] + Lang::get('lists.purchaseOrder.status');

		return view('pages.receivePO.show', compact('purchaseOrder', 'receiveArticles', 'statuses'));
	}

    /**
     * Merge a list Purchase Order Details and their Articles
     */
    public function mergePODetailWithArticle($id) {
        $results = [];
        // filter to get purchaseOrderDetails
        $filter = [
            'Order_Number' => $id,
        ];
        // get the PurchaseOrderDetails
        $purchaseOrderDetails = $this->purchaseOrderDetailRepository->filterOn($filter, 0);
        //dd(__METHOD__."(".__LINE__.")",compact('purchaseOrderDetails'));
        if(isset($purchaseOrderDetails) && count($purchaseOrderDetails)) {

            // foreach purchaseOrderDetail record, merge with Article
            for($i = 0; $i < count($purchaseOrderDetails); $i++) {
                $article = $this->articleRepository->find($purchaseOrderDetails[$i]->SKU);

                if(isset($article)) {
                    // build results
                    $result = [
                        'purchaseOrderDetailID' => $purchaseOrderDetails[$i]->objectID,
                        'Expected_Qty'          => $purchaseOrderDetails[$i]->Expected_Qty,
                        'articleID'             => $article->objectID,
                        'Client_SKU'            => $article->Client_SKU,
                        'Description'           => $article->Description,
                        'UOM'                   => $article->UOM,
                        'Case_Pack'             => $article->Case_Pack,
                        'Colour'                => $article->Colour,
                        'Zone'                  => $article->Zone,
                        'rework'                => isset($article->rework) ? $article->rework : Lang::get('labels.rework_unknown'),
                    ];
                    $results[$article->Client_SKU] = $result;
                }
            }

        }
        ksort($results);
        //dd($results);
        $perPage = 10;
        if(Request::has('page')) {
            $bypass = $perPage * (Request::get('page') - 1) * -1;
        } else {
            $bypass = 0;
        }
        $thisPage=[];
        foreach($results as $key => $value) {
            $bypass++;
            if($bypass > 0) {
                $thisPage[] = $value;
            }
            if(count($thisPage) == $perPage) break;
        }
        $currentURL = Request::url();
        //dd(__METHOD__."(".__LINE__.")",compact('thisPage','results','bypass', 'currentURL'));
        return new LengthAwarePaginator($thisPage, count($results), $perPage, Request::get('page'), ['path' => Request::url()]);
    }
}
