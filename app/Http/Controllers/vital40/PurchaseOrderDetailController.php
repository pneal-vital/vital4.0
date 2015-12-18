<?php namespace App\Http\Controllers\vital40;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use \Lang;
use \Request;

/**
 * Class PurchaseOrderDetailController
 * @package App\Http\Controllers
 */
class PurchaseOrderDetailController extends Controller {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital40\Repositories\PurchaseOrderDetailRepositoryInterface
	 */ 
	protected $purchaseOrderDetailRepository;

	/**
	 * Constructor requires purchaseOrderDetail Repository
	 */ 
	public function __construct(
          PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
    ) {
		$this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
	}

	/**
	 * Display a Listing of the resource.
	 */
	public function index() {
        $purchaseOrderDetail = Request::all();
        if(count($purchaseOrderDetail) == 0) {
            // lets provide a default filter
            $purchaseOrderDetail['Created'] = Carbon::today()->format('Y-m');
        }
		// using an implementation of the PurchaseOrderDetail Repository Interface
		$purchaseOrderDetails = $this->purchaseOrderDetailRepository->paginate($purchaseOrderDetail);

		// Using the view(..) helper function
		return view('pages.purchaseOrderDetail.index', compact('purchaseOrderDetail', 'purchaseOrderDetails'));
	}

	/**
	 * Display a Filtered Listing of the resource.
	 */
	public function filter() {
		$purchaseOrderDetail = Request::all();

		// using an implementation of the PurchaseOrderDetail Repository Interface
		$purchaseOrderDetails = $this->purchaseOrderDetailRepository->paginate($purchaseOrderDetail);

		// populate a View
		return View::make('pages.purchaseOrderDetail.index', compact('purchaseOrderDetail', 'purchaseOrderDetails'));
	}

	/**
	 * display the specific resource
	 */
	public function show($id) {
		// using an implementation of the PurchaseOrderDetail Repository Interface
		$purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($id);

		return view('pages.purchaseOrderDetail.show', compact('purchaseOrderDetail'));
	}

}
