<?php namespace App\vital40\Receive;
/**
 *
 * Created by PhpStorm.
 * User: pneal
 * Date: 29May2015
 * Time: 10:59 AM
 */

use Carbon\Carbon;
use vital3\Repositories\EventQueueRepositoryInterface;
use vital3\Repositories\InventoryRepositoryInterface;
use vital3\Repositories\LocationRepositoryInterface;
use vital3\Repositories\PalletRepositoryInterface;
use vital3\Repositories\UOMRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\PurchaseOrderRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use vital40\Repositories\VendorComplianceRepositoryInterface;
use \App;
use \Auth;
use \Config;
use \Lang;
use \Log;
use \Session;

class POReconciliationFlow {
    const NO_TEXT   = 'noText';
    const OBJECT    = 'object';
    const OBJECT_ID = 'objectID';
    const PALLET    = 'Pallet';
    const TEXT      = 'Text';
    const TOTE      = 'Tote';
    const TYPE      = 'type';
    const UNKNOWN   = 'Unknown';
    const UPC       = 'UPC';

    /**
     * Reference an implementation of the Repository Interface
     * @var vital40\Repositories\ArticleRepositoryInterface
     */
    protected $articleRepository;
    protected $eventQueueRepository;
    protected $inventoryRepository;
    protected $locationRepository;
    protected $palletRepository;
    protected $purchaseOrderDetailRepository;
    protected $purchaseOrderRepository;
    protected $receiptHistoryRepository;
    protected $toteRepository;
    protected $uomRepository;
    protected $upcRepository;
    protected $userActivityRepository;
    protected $vendorComplianceRepository;


    /**
     * Constructor requires article Repository
     */
    public function __construct(
              ArticleRepositoryInterface $articleRepository
            , EventQueueRepositoryInterface $eventQueueRepository
            , InventoryRepositoryInterface $inventoryRepository
            , LocationRepositoryInterface $locationRepository
            , PalletRepositoryInterface $palletRepository
            , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
            , PurchaseOrderRepositoryInterface $purchaseOrderRepository
            , ReceiptHistoryRepositoryInterface $receiptHistoryRepository
            , ToteRepositoryInterface $toteRepository
            , UOMRepositoryInterface $uomRepository
            , UPCRepositoryInterface $upcRepository
            , UserActivityRepositoryInterface $userActivityRepository
            , VendorComplianceRepositoryInterface $vendorComplianceRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->eventQueueRepository = $eventQueueRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->locationRepository = $locationRepository;
        $this->palletRepository = $palletRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->toteRepository = $toteRepository;
        $this->uomRepository = $uomRepository;
        $this->upcRepository = $upcRepository;
        $this->userActivityRepository = $userActivityRepository;
        $this->vendorComplianceRepository = $vendorComplianceRepository;
    }

    /**
     * Receiver Selects PO.
     * Record the selection of a Purchase Order by user of this session.
     * @param $purchaseOrder
     */
    public function selectPO($purchaseOrder) {

        $this->userActivityRepository->associate($purchaseOrder->Purchase_Order
                , Config::get('constants.userActivity.classID.POReconciliation')
                , Lang::get('internal.userActivity.purpose.poReconciliation', ['id' => $purchaseOrder->Purchase_Order]));

    }

    /**
     * Confirm a Purchase Order
     * 1. VITaL receives ONLY “expected” inventory on UPC level (no over or short receipts)
     * 2. VITaL processes required +/- adjustments using HH reason code
     *    Adjustments processed under user ID that executed PO close
     * 3. VITaL stores / writes PO / article / UPC / variance details for vendor compliance reporting
     *    Report to be scoped
     * @param $podID
     */
    public function confirm($poID) {
        Log::debug(__METHOD__."(".__LINE__."):  confirm: $poID");

        $purchaseOrder = $this->purchaseOrderRepository->find($poID);
        //dd($po);

        // scan each Purchase Order Detail line
        $filter = [
            'Order_Number' => $purchaseOrder->objectID,
        ];
        $purchaseOrderDetails = $this->purchaseOrderDetailRepository->filterOn($filter);
        //dd(count($purchaseOrderDetails));
        if(isset($purchaseOrderDetails) && count($purchaseOrderDetails)) {

            // foreach purchaseOrderDetail record, merge with Article
            foreach($purchaseOrderDetails as $purchaseOrderDetail) {
                //scan each UPC in the Article
                $article = $this->articleRepository->find($purchaseOrderDetail->SKU);

                // $received is calculated from Inventory in status received
                $invFilter = [
                    'Status'     => [Config::get('constants.inventory.status.received'),Config::get('constants.inventory.status.putAway')],
                    'Order_Line' => $purchaseOrderDetail->objectID,
                ];
                $upcs = $this->upcRepository->getArticleUPCs($article->objectID);
                foreach($upcs as $upc) {
                    # accumulate expected and received
                    $expected = $purchaseOrderDetail->Expected_Qty * $upc->Quantity;
                    $invFilter['Item'] = $upc->objectID;
                    // $received is calculated from ReceiptHistory counting Received UPC into Tote... entries
                    $filter = [
                        'POD'      => $purchaseOrderDetail->objectID,
                        'UPC'      => $upc->objectID,
                        'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote', ['time' => '%', 'upcSKU' => '%', 'n' => '%', 'ofn' => '%'])),
                    ];
                    $received = $this->receiptHistoryRepository->countOn($filter);
                    # where expected and received differ, insert an adjustment record for the differences
                    if($expected != $received) {
                        $objectID = $purchaseOrderDetail->objectID;
                        // attempt to get an Inventory->objectID
                        $inventory = $this->inventoryRepository->filterOn($invFilter,1);
                        if(isset($inventory) && count($inventory) > 0 && isset($inventory[0]->objectID)) {
                            $objectID = $inventory[0]->objectID;
                        }
                        // insert an adjustment
                        $this->insertAdjustment($objectID, $expected, $received, $poID);
                    }
                    $this->insertVendorCompliance($purchaseOrder->Purchase_Order, $purchaseOrderDetail->objectID, $article->objectID, $upc->objectID, $expected, $received);
                }

                // we are done with the Purchase Order Detail
                $status = Config::get('constants.purchaseOrderDetail.status.confirmed');
                $this->purchaseOrderDetailRepository->update($purchaseOrderDetail->objectID, ['Status' => $status]);
            }
        }

        // we are done with the Purchase Order
        $status = Config::get('constants.purchaseOrder.status.confirmed');
        $this->purchaseOrderRepository->update($purchaseOrder->objectID, ['Status' => $status]);
    }

    /**
     * Create event 302 Adjustment
     * 2. VITaL processes required +/- adjustments using HH reason code
     *    Adjustments processed under user ID that executed PO close
     * @param $objectID - object that caused the adjustment request
     * @param $oldQty   - inventory.quantity before the adjustment (expected)
     * @param $newQty   - inventory.quantity after the adjustment (actual)
     * @param $poID     - Purchase_Order that caused the adjustment
     */
    protected function insertAdjustment($objectID, $oldQty, $newQty, $poID) {
        Log::debug(__METHOD__."(".__LINE__."):  insertAdjustment($objectID, $oldQty, $newQty, $poID)");

        $eventID = Config::get('constants.event.302.eventID');
        $params = [
            'oldQty'     => $oldQty,
            'newQty'     => $newQty,
            'reasonCode' => Config::get('constants.event.302.reasonCode.vital_HH'),
            'poNumber'   => $poID,
            'objectID'   => $objectID,
            'username'   => Auth::user()->name,
        ];
        $priority = Config::get('constants.event.302.priority');
        $this->eventQueueRepository->create(['eventID' => $eventID, 'parameters' => $params, 'priority' => $priority]);
    }

    /**
     * Create Vendor Compliance Record
     * 3. VITaL stores / writes PO / article / UPC / variance details for vendor compliance reporting
     * store PO / article / UPC / variance details for vendor compliance reporting
     *
     * @param $objectID - object that caused the adjustment request
     * @param $oldQty   - inventory.quantity before the adjustment (expected)
     * @param $newQty   - inventory.quantity after the adjustment (actual)
     * @param $poID     - Purchase_Order that caused the adjustment
     */
    protected function insertVendorCompliance($poID, $podID, $articleID, $upcID, $expectedQty, $receivedQty) {
        Log::debug(__METHOD__."(".__LINE__."):  insertVendorCompliance($poID, $podID, $articleID, $upcID, $expectedQty, $receivedQty)");

        $params = [
            'poID'        => $poID,
            'podID'       => $podID,
            'articleID'   => $articleID,
            'upcID'       => $upcID,
            'expectedQty' => $expectedQty,
            'receivedQty' => $receivedQty,
        ];
        $this->vendorComplianceRepository->create($params);
    }

    /**
     * Build a grid of the current state of receiving UPCs for this purchaseOrderDetail and article
     *
     * @param $podID
     * @param $articleID
     * @return array
     */
    public function receivedUPCsGrid($podID, $articleID) {
        $results = [];

        // get the PurchaseOrderDetail
        $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($podID);
        // cases expected
        $casesExpected = $purchaseOrderDetail->Expected_Qty;

        // get the Article
        //$article = $this->articleRepository->find($articleID);

        // UPCs
        $upcs = $this->upcRepository->getArticleUPCs($articleID);

        //dd($upcs);
        if(isset($upcs) && count($upcs)) {

            // foreach upc record
            for($i = 0; $i < count($upcs); $i++) {
                $expected = $casesExpected * $upcs[$i]->Quantity;

                // $received is calculated from Inventory in status received
                $filter = [
                    'Status'     => [Config::get('constants.inventory.status.received'),Config::get('constants.inventory.status.putAway')],
                    'Order_Line' => $podID,
                    'Item'       => $upcs[$i]->objectID,
                ];
                $received = $this->inventoryRepository->quantityOn($filter);

                // build results
                $result = [
                    'status'                  => $this->calculateStatus($expected, $received),
                    'upc'                     => $upcs[$i]->Client_SKU,
                    'caseUnitQuantity'        => $upcs[$i]->Quantity,
                    'expectedUnitQuantity'    => $expected,
                    'receivedUnitQuantity'    => $received,
                    'outstandingUnitQuantity' => $expected - $received,
                    // TODO - tote and toteLocation need vitaldev Generic_Container, Location, & container objects.
                    'tote'                    => '',
                    'location'                => '',
                ];
                $results[$this->calculateKey($result)] = $result;
            }

            ksort($results);
        }

        //dd($results);
        return $results;
    }

    /**
     * calculate status from $expected, $received
     *
     * @param $expected
     * @param $received
     * @return string
     */
    protected function calculateStatus($expected, $received) {
        if ($received == 0) $status = '';
        elseif ($received == $expected) $status = 'success';
        elseif ($received > $expected) $status = 'warning';
        else $status = 'active';
        return $status;
    }

    /**
     * calculate UPC line key
     *
     * @param $result
     * @return string
     */
    protected function calculateKey($result) {
        $key = 10 + strpos(',success,warning,active,,', ','.$result['status'].',');

        $key .= '_' . $result['upc'];
        return $key;
    }

}
