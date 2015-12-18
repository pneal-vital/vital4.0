<?php namespace App\Http\Controllers\vital40;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use vital3\Repositories\ClientRepositoryInterface;
use vital40\Repositories\PurchaseOrderRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use \Lang;
use \Request;

/**
 * Class PurchaseOrderController
 * @package App\Http\Controllers
 */
class PurchaseOrderController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\PurchaseOrderRepositoryInterface
	 */
    protected $clientRepository;
    protected $purchaseOrderDetailRepository;
	protected $purchaseOrderRepository;

	/**
	 * Constructor requires purchaseOrder Repository
	 */ 
	public function __construct(
          ClientRepositoryInterface $clientRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , PurchaseOrderRepositoryInterface $purchaseOrderRepository
    ) {
		$this->clientRepository = $clientRepository;
		$this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
	}


	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $purchaseOrder = Request::all();
        //dd(__METHOD__."(".__LINE__.")",compact('purchaseOrder'));
        if(count($purchaseOrder) == 0) {
            // lets provide a default filter
            $client = $this->clientRepository->filterOn(['Client_Name' => 'LCL'], 1);
            $purchaseOrder['Client'] = $client->objectID;
            $purchaseOrder['Expected'] = Carbon::today()->format('Y-m');
        }

		// using an implementation of the PurchaseOrder Repository Interface
		$purchaseOrders = $this->purchaseOrderRepository->paginate($purchaseOrder);

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status')] + Lang::get('lists.purchaseOrder.status');

		// Using the view(..) helper function
		return view('pages.purchaseOrder.index', compact('purchaseOrder', 'purchaseOrders', 'statuses'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$purchaseOrder = Request::all();

		// using an implementation of the PurchaseOrder Repository Interface
		$purchaseOrders = $this->purchaseOrderRepository->paginate($purchaseOrder);

        // possible Statuses
        $statuses = [Lang::get('labels.enter.Status')] + Lang::get('lists.purchaseOrder.status');

		// populate a View
		return View::make('pages.purchaseOrder.index', compact('purchaseOrder', 'purchaseOrders', 'statuses'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {
		// using an implementation of the PurchaseOrder Repository Interface
		$purchaseOrder = $this->purchaseOrderRepository->find($id);

		$filter = [
			'Order_Number' => $purchaseOrder->objectID,
		];
		// using an implementation of the PurchaseOrderDetail Repository Interface
		$purchaseOrderDetails = $this->purchaseOrderDetailRepository->paginate($filter);

		return view('pages.purchaseOrder.show', compact('purchaseOrder', 'purchaseOrderDetails'));
	}

}
