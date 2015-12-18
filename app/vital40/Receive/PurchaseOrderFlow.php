<?php namespace App\vital40\Receive;
/**
 *
 * Created by PhpStorm.
 * User: pneal
 * Date: 05/03/15
 * Time: 12:03 PM
 */

use Illuminate\Support\Facades\Lang;
use vital40\Repositories\PurchaseOrderRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use \Config;

class PurchaseOrderFlow {

    /**
     * Reference an implementation of the Repository Interface
     * @var vital40\Repositories\PurchaseOrderRepositoryInterface
     */
    protected $purchaseOrderRepository;
    protected $purchaseOrderDetailRepository;
    protected $articleRepository;
    protected $userActivityRepository;


    /**
     * Constructor requires purchaseOrder Repository
     */
    public function __construct(PurchaseOrderRepositoryInterface $purchaseOrderRepository
            , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
            , ArticleRepositoryInterface $articleRepository
            , UserActivityRepositoryInterface $userActivityRepository) {
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->articleRepository = $articleRepository;
        $this->userActivityRepository = $userActivityRepository;
    }

    public function associate($purchaseOrder) {
        //dd($purchaseOrder);

        $this->userActivityRepository->associate($purchaseOrder->Purchase_Order
                                        , Config::get('constants.userActivity.classID.ReceivePO')
                                        , Lang::get('internal.userActivity.purpose.receivePO', ['id' => $purchaseOrder->Purchase_Order]));

    }

}
