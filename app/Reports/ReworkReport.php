<?php namespace App\Reports;
/**
 * Created by PhpStorm.
 * User: pneal
 * Date: 30/03/16
 * Time: 1:49 PM
 */

use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use \Lang;
use \Log;

/**
 * Class ReworkReport
 * @package App\Reports
 */
class ReworkReport implements ReworkReportInterface {

    /**
     * Reference an implementation of the Repository Interface
     */
    protected $articleRepository;
    protected $purchaseOrderDetailRepository;
    protected $receiptHistoryRepository;
    protected $upcRepository;


    /**
     * Constructor requires PerformanceTally Repository
     */
    public function __construct(
          ArticleRepositoryInterface $articleRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , ReceiptHistoryRepositoryInterface $receiptHistoryRepository
        , UPCRepositoryInterface $upcRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->upcRepository = $upcRepository;
    }

    /**
     * Generate a Rework Report.
     */
    public function generate($fromDate, $toDate, $limit = 10) {
        Log::debug('fromDate: '.$fromDate.',  toDate: '.$toDate.', limit: '.$limit);
        $pods = [];
        $results = [];

        // filter to count received
        $filter = [
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote')),
        ];
        $receiptHistories = $this->receiptHistoryRepository->filterOn($filter, $limit);

        //dd(__METHOD__."(".__LINE__.")",compact('fromDate','toDate','limit','receiptHistories','pods','results'));
        Log::debug('count(receiptHistories): '.count($receiptHistories));

        foreach($receiptHistories as $receiptHistory) {
            // can we use this record?
            if(isset($receiptHistory['PO']) == false
                || isset($receiptHistory['POD']) == false
                || isset($receiptHistory['Article']) == false
                || isset($receiptHistory['UPC']) == false) {
                continue;
            }
            $podID = $receiptHistory['POD'];
            $articleID = $receiptHistory['Article'];
            $upcID = $receiptHistory['UPC'];

            // if we don't already know, calculate Expected_Qty (case level) and expected (UPC level)
            if(isset($pods[$podID]) == false) {
                $pods[$podID] = [];
                $pod = $this->purchaseOrderDetailRepository->find($podID);
                Log::debug('podID: '.$podID.',  pod->Expected_Qty: '.$pod->Expected_Qty);
                $pods[$podID]['Expected_Qty'] = $pod->Expected_Qty;
                $pods[$podID]['Status'] = $pod->Status;
            }
            if(isset($pods[$articleID]) == false) {
                $pods[$articleID] = [];
                $article = $this->articleRepository->find($articleID);
                Log::debug('articleID: '.$articleID.',  article->rework: '.$article->rework);
                $pods[$articleID]['rework'] = $article->rework;
            }
            if(isset($pods[$podID][$upcID]) == false) {
                $pods[$podID][$upcID] = [];
                $article = $this->articleRepository->find($articleID);
                $pods[$articleID]['rework'] = $article->rework;
                $upc = $this->upcRepository->find($upcID);
                $pods[$podID][$upcID]['Client_SKU'] = $upc->Client_SKU;
                $expected = 0;
                if(isset($upc->parents[$articleID]) && isset($upc->parents[$articleID]->Quantity)) {
                    Log::debug('upcID: '.$upcID.',  upc->parents['.$articleID.']->Quantity: '.$upc->parents[$articleID]->Quantity);
                    $expected = $upc->parents[$articleID]->Quantity * $pods[$podID]['Expected_Qty'];
                }
                $pods[$podID][$upcID]['expected'] = $expected;
            }
            //dd(__METHOD__."(".__LINE__.")",compact('fromDate','toDate','limit','receiptHistories','receiptHistory','podID','articleID','upcID','pods','results'));

            // at this point we should have all the data we want
            $key = $receiptHistory['PO'].",$podID,$upcID,".$receiptHistory['User_Name'];
            if(isset($results[$key])) {
                $result = $results[$key];
                $result->Actual_Qty++;
                $varVal = abs($result->Actual_Qty - $result->Expected_Qty);
                $varStr = ($result->Expected_Qty == $result->Actual_Qty ? '' : ($result->Expected_Qty < $result->Actual_Qty ? 'over' : 'short'));
                $result->Variance = "$varVal $varStr";
                if($result->fromDate > $receiptHistory['created_at']) $result->fromDate = $receiptHistory['created_at'];
                if($result->toDate   < $receiptHistory['created_at']) $result->toDate   = $receiptHistory['created_at'];
                $results[$key] = $result;
            } else {
                $expected = $pods[$podID][$upcID]['expected'];
                $received = 1;
                $varVal = abs($received - $expected);
                $varStr = ($expected == $received ? '' : ($expected < $received ? 'over' : 'short'));
                $results[$key] = (object)[
                    'Purchase_Order' => $receiptHistory['PO']
                    , 'PO_Class'       => 'Vendor'
                    , 'Client_SKU'     => $pods[$podID][$upcID]['Client_SKU']
                    , 'upcID'          => $upcID
                    , 'Expected_Qty'   => $expected
                    , 'Actual_Qty'     => $received
                    , 'Variance'       => "$varVal $varStr"
                    , 'fromDate'       => $receiptHistory['created_at']
                    , 'toDate'         => $receiptHistory['created_at']
                    , 'User_Name'      => $receiptHistory['User_Name']
                    , 'Status'         => $pods[$podID]['Status']
                    , 'rework'         => $pods[$articleID]['rework']
                ];
            }
        }
        ksort($results);
        //dd(__METHOD__."(".__LINE__.")",compact('fromDate','toDate','limit','receiptHistories','pods','results'));
        Log::debug('count(results): '.count($results));

        return array_values($results);
    }

}
