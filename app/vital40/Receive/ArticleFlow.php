<?php namespace App\vital40\Receive;
/**
 *
 * Created by PhpStorm.
 * User: pneal
 * Date: 05/03/15
 * Time: 12:03 PM
 */

use Carbon\Carbon;
use vital3\Repositories\InventoryRepositoryInterface;
use vital3\Repositories\LocationRepositoryInterface;
use vital3\Repositories\PalletRepositoryInterface;
use vital3\Repositories\UOMRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\PerformanceTallyRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\PurchaseOrderRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use vital40\Repositories\SessionTypeRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use \App;
use \Auth;
use \Config;
use \Lang;
use \Log;
use \Request;
use \Session;


class ArticleFlow {
    const CLOSE_TOTE     = 'Close_Tote';
    const NO_TEXT        = 'noText';
    const NO_TEXT_SERIAL = 'a:2:{s:4:"type";s:6:"noText";s:8:"objectID";i:0;}';
    const OBJECT         = 'object';
    const OBJECT_ID      = 'objectID';
    const PALLET         = 'Pallet';
    const PREVIOUS       = 'previous';
    const PREVIOUS_TEXT  = 'previousText';
    const RECEIVE_UPC    = 'Receive_UPC';
    const TEXT           = 'Text';
    const TOTE           = 'Tote';
    const TYPE           = 'type';
    const UNKNOWN        = 'unknown';
    const UNKNOWN_SERIAL = 'a:2:{s:4:"type";s:7:"unknown";s:8:"objectID";i:0;}';
    const UPC            = 'UPC';

    /**
     * Reference an implementation of the Repository Interface
     * @var vital40\Repositories\ArticleRepositoryInterface
     */
    protected $articleRepository;
    protected $inventoryRepository;
    protected $locationRepository;
    protected $palletRepository;
    protected $performanceTallyRepository;
    protected $purchaseOrderDetailRepository;
    protected $purchaseOrderRepository;
    protected $receiptHistoryRepository;
    protected $sessionTypeRepository;
    protected $toteRepository;
    protected $uomRepository;
    protected $upcRepository;
    protected $userActivityRepository;


    /**
     * Constructor requires article Repository
     */
    public function __construct(
          ArticleRepositoryInterface $articleRepository
        , InventoryRepositoryInterface $inventoryRepository
        , LocationRepositoryInterface $locationRepository
        , PalletRepositoryInterface $palletRepository
        , PerformanceTallyRepositoryInterface $performanceTallyRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , PurchaseOrderRepositoryInterface $purchaseOrderRepository
        , ReceiptHistoryRepositoryInterface $receiptHistoryRepository
        , SessionTypeRepositoryInterface $sessionTypeRepository
        , ToteRepositoryInterface $toteRepository
        , UOMRepositoryInterface $uomRepository
        , UPCRepositoryInterface $upcRepository
        , UserActivityRepositoryInterface $userActivityRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->locationRepository = $locationRepository;
        $this->palletRepository = $palletRepository;
        $this->performanceTallyRepository = $performanceTallyRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->sessionTypeRepository = $sessionTypeRepository;
        $this->toteRepository = $toteRepository;
        $this->uomRepository = $uomRepository;
        $this->upcRepository = $upcRepository;
        $this->userActivityRepository = $userActivityRepository;
    }

    /**
     * Receiver Selects PO.
     * Record the selection of a Purchase Order by user of this session.
     * @param $purchaseOrder
     */
    public function selectPO($purchaseOrder) {
        //dd(__METHOD__.'('.__LINE__.')', compact('purchaseOrder'));

        $this->userActivityRepository->associate($purchaseOrder->Purchase_Order
                , Config::get('constants.userActivity.classID.ReceivePO')
                , Lang::get('internal.userActivity.purpose.receivePO', ['id' => $purchaseOrder->Purchase_Order]));

    }

    /**
     * Receiver Selects Location.
     * Record the selection of a Work table by user of this session.
     * @param $location
     */
    public function selectLocation($location) {
        //dd(__METHOD__.'('.__LINE__.')', compact('purchaseOrder'));

        $this->userActivityRepository->associate($location->objectID
                , Config::get('constants.userActivity.classID.ReceiveLocation')
                , Lang::get('internal.userActivity.purpose.receiveLocation', ['name' => $location->Location_Name]));

    }

    /**
     * Receiver Selects Article.
     * Record the selection of an Article by user of this session.
     * @param $purchaseOrderDetail
     * @param $article
     */
    public function selectArticle($purchaseOrderDetail, $article) {
        //dd(__METHOD__.'('.__LINE__.')', compact('article'));
        // try var_dump($article);

        $this->userActivityRepository->associate($purchaseOrderDetail->objectID
                , Config::get('constants.userActivity.classID.ReceiveArticle')
                , Lang::get('internal.userActivity.purpose.receiveArticle', ['id' => $article->UPC]));

    }

    /**
     * Connect a PurchaseOrderDetail line with a location.
     * if user has selected a purchaseOrderDetail line, and a location
     * then we should associate the purchaseOrderDetail line with that location
     * @param $podID
     * @param $locationID
     */
    public function setPurchaseOrderDetailLocation($podID, $locationID) {
        $this->purchaseOrderDetailRepository->update($podID, ['Location' => $locationID]);
    }

    /**
     * Request rework value, returns true if rework value is not set.
     * @param $articleID
     * @return bool True if rework value is not set.
     */
    public function requestReworkValue($articleID) {
        Log::debug("articleID: $articleID");

        $article = $this->articleRepository->find($articleID);

        if(!property_exists($article, 'rework') || strlen($article->rework) == 0) {
            return true;
        }
        return false;
    }

    /**
     * Request rework value, returns true if rework value is not set.
     * @param $articleID
     * @return bool True if rework value is not set.
     */
    public function setReworkValue($articleID, $value) {
        Log::debug("articleID: $articleID, value: $value");

        $article = $this->articleRepository->find($articleID);

        $result = $this->articleRepository->update($article, ['rework' => $value]);

        return $result;
    }

    /**
     * Answers the question, is this article complete.
     * An article is considered complete when;
     * 1. all UPCs for this article have been received
     * 2. number received for each UPC == expected
     * .. more to come ..
     * @param $articleID
     * @return bool True if rework value is not set.
     */
    public function isArticleComplete($purchaseOrderDetail, $article) {
        Log::debug("podID: {$purchaseOrderDetail->objectID}, articleID: {$article->UPC}");

        //$article = $this->articleRepository->find($articleID);

        //$result = $this->articleRepository->update($article, ['rework' => $value]);

        return null;
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

        //dd(__METHOD__.'('.__LINE__.')', compact('upcs'));
        if(isset($upcs) && count($upcs)) {

            // foreach upc record
            for($i = 0; $i < count($upcs); $i++) {
                $expected = $casesExpected * $upcs[$i]->Quantity;

                // $received is calculated from ReceiptHistory counting Received UPC into Tote... entries
                $filter = [
                    'POD'      => $podID,
                    'UPC'      => $upcs[$i]->objectID,
                    'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote')),
                ];
                $received = $this->receiptHistoryRepository->countOn($filter);

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

        //dd(__METHOD__.'('.__LINE__.')', compact('results'));
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

    /**
     * This is what we are receiving here
    $newText = [
        'POD' => 6232063899       - PurchaseOrderDetail->objectID,
        'Article' => 6217093230   - Article->objectID,
        'User_Name' => pneal      - Auth::user()->name,
        'Sender_Name' => pneal    - Auth::user()->name,
        'Text' => 63664347362     - text entered by the worker
        'clicked' => 'text_entry' - button name user entered, in this case user pressed Enter in text_entry field
    ];
     */
    public function textEntry($newText) {
        $sid = Request::session()->getId();
        Log::debug('Start of textEntry($newText): User: '.Auth::user()->name.', sid: '.$sid);

        /*
         *  What have we in the current session?
         *
         * Session::get(<name>,<default value>);
         * =====================================
         * It would appear this is not as simple as expected.
         * Tried using Session::put('previous',serialize($entry))
         * and then here retrieve it with unserialize(Session::get('previous', self::UNKNOWN_SERIAL));
         * But this proved to be unreliable. It appears that other intermingled transactions are using
         * Session, and the value for 'previous' is often lost.
         *
         * sessionTypeRepository
         * =====================
         * sessionTypeRepository was created to replace the 'previous' held by Session. I have full control
         * over which transactions make use sessionTypeRepository. textEntry is the only method that uses
         * sessionTypeRepository with id => 'previous' thus isolating the issue.
         */
        //$previous = (object) unserialize(Session::get(self::PREVIOUS, self::UNKNOWN_SERIAL));
        $previous = (object) unserialize($this->sessionTypeRepository->get(self::PREVIOUS, self::UNKNOWN_SERIAL));
        Log::debug('pervious: '.serialize($previous));

        // interpret the netText
        $entry = $this->interpretNewText($newText, $previous);

        /*
         * And then a miracle happens ...
         *
         * Ok, so we have to decide what the user is trying to do
         * 1. if they previously scanned a UPC, and now scanned a Tote
         *   they want to put the UPC into the Tote
         * 2. if they previously scanned a Tote, and now scanned a Pallet
         *   they want to close the Tote and place it on the Pallet
         * 3. if they scanned a UPC, display the details
         *   ask to put it in the same tote
         * 4. ???
         */
        $saveAsPrevious = false;
        $responseKey = [];
        if($previous->type == self::UPC && $entry->type == self::UNKNOWN) {
            $responseKey = $this->creatingNewTote($previous, $entry);
        }
        if($entry->type == self::NO_TEXT || $entry->type == self::UNKNOWN) {
            $saveAsPrevious = false;
        } elseif($previous->type == self::UPC && $entry->type == self::TOTE) {
            if(!isset($responseKey['error']))
                $responseKey = array_merge($responseKey, $this->scannedTote($previous, $entry));
            if(!isset($responseKey['error']))
                $responseKey = array_merge($responseKey, $this->putUPCinTote($previous, $entry));
            $saveAsPrevious = false;
        } elseif($previous->type == self::TOTE && $entry->type == self::PALLET) {
            $responseKey = $this->closeTote($previous, $entry);
            $saveAsPrevious = false;
        } elseif($entry->type == self::UPC) {
            $responseKey = $this->scannedUPC($entry);
            $saveAsPrevious = true;
        } elseif($entry->type == self::TOTE) {
            $responseKey = $this->scannedTote($previous, $entry);
            $saveAsPrevious = true;
        } else {
            $responseKey['mode'] = ($previous->type == self::UPC ? self::RECEIVE_UPC : self::CLOSE_TOTE);
            $saveAsPrevious = false;
        }
        $responseKey[self::TEXT] = $newText[self::TEXT];
        $responseKey[self::PREVIOUS_TEXT] = isset($previous->Text) ? $previous->Text : '';

        Log::debug('check for responseKey[errors]');
        if(!isset($responseKey['error'])) {
            // save Session data for next round
            if($saveAsPrevious && stripos(self::UPC.', '.self::TOTE.', '.self::PALLET, $entry->type) !== false) {
                //Session::put(self::PREVIOUS, serialize($entry));
                $this->sessionTypeRepository->put(self::PREVIOUS, serialize($entry));
                Log::debug('set pervious: '.serialize($entry));
            } elseif($saveAsPrevious == false && $entry->type == self::UNKNOWN) {
                Log::debug("entry: don't save previous when type is unknown");
            } else {
                Log::debug('entry: '.($saveAsPrevious ? 'saveAsPrevious, ' : 'forget previous, ').(isset($entry->type) ? $entry->type : 'entry->type is not set'));
                //Session::forget(self::PREVIOUS);
                $this->sessionTypeRepository->delete(self::PREVIOUS);
            }
        }

        // build our response
        $response = $this->buildResponse($newText, $entry, $responseKey);
        Log::debug('response', $response);

        return $response;
    }

    /**
     * determine if newText->Text contains a UPC->UPC, Tote->Carton_ID, or Pallet->Pallet_ID
     * @param $newText
     * @return mixed
     */
    protected function interpretNewText(&$newText, $previous) {
        Log::debug('newText:', $newText);
        // user didn't scan anything, probably just hit a button
        if(!isset($newText[self::TEXT])) {
            $entry = (object) unserialize(self::NO_TEXT_SERIAL);
        } else {
            $newText[self::TEXT] = trim($newText[self::TEXT]);
            if(strlen($newText[self::TEXT]) == 0) {
                $entry = (object) unserialize(self::NO_TEXT_SERIAL);
            } elseif(strlen($newText[self::TEXT]) < 4) {
                $entry = (object) unserialize(self::UNKNOWN_SERIAL);
            } else {
                $filter = [
                    'UPC'       => $newText[self::TEXT],
                    'Carton_ID' => $newText[self::TEXT],
                    'Pallet_ID' => $newText[self::TEXT],
                    'Pallet_ID.prefix' => ['RES', 'FWP'],
                ];
            }
        }
        if(!isset($entry)) {
            $upcs = $this->upcRepository->filterOn($filter, 2);
            if(isset($upcs) && count($upcs) == 1) {
                $entry = (object) [self::TYPE => self::UPC, self::OBJECT_ID => $upcs[0]->objectID, self::TEXT => $newText[self::TEXT]];
            }
        }
        if(!isset($entry)) {
            Session::forget('toteID');
            $totes = $this->toteRepository->filterOn($filter, 2);
            if(isset($totes) && count($totes) == 1) {
                Session::set('toteID', $totes[0]->objectID);
                $entry = (object) [self::TYPE => self::TOTE, self::OBJECT_ID => $totes[0]->objectID, self::TEXT => $newText[self::TEXT]];
            }
        }
        if(!isset($entry)) {
            $pallets = $this->palletRepository->filterOn($filter, 2);
            if(isset($pallets) && count($pallets) == 1) {
                $entry = (object) [self::TYPE => self::PALLET, self::OBJECT_ID => $pallets[0]->objectID, self::TEXT => $newText[self::TEXT]];
            }
        }
        if(!isset($entry)) {
            //$entry = (object) unserialize(self::UNKNOWN_SERIAL);
            $entry = (object) [self::TYPE => self::UNKNOWN, self::OBJECT_ID => 0, self::TEXT => $newText[self::TEXT]];
        }

        Log::debug('entry: '.serialize($entry));
        return $entry;
    }

    protected function creatingNewTote($previous, &$entry) {
        $result = ['mode' => self::RECEIVE_UPC];

        // is entered text formatted like a Carton_ID?
        $regex = '/^\d{2} \d{4} \d{4}$/';
        if(isset($entry->Text) and preg_match($regex, $entry->Text)) {
            Log::debug("formatted as Carton_ID: $entry->Text");
            $entry->type = self::TOTE;

            // does this UPC have an open tote?
            $anotherOpenTote = $this->upcHasOpenTote($previous->objectID);
            if($anotherOpenTote != '0') {
                $responseKey = ['key' => 'creatingNewTote', 'upcID' => $previous->objectID, 'cartonID' => $entry->Text, 'mode' => self::RECEIVE_UPC
                    , 'error' => Lang::get('internal.errors.tote.anotherOpenTote', ['upcID' => $previous->Text, 'tote' => $entry->Text])
                    , 'time' => Carbon::now()];
                return $responseKey;
            }

            $tote = $this->toteRepository->findOrCreate(['Carton_ID' => $entry->Text]);
            if(isset($tote)) {
                $entry->objectID = $tote->objectID;
                $result['closeTote'] = 'refresh';
            }
        }
        return $result;
    }

    protected function putUPCinTote($previous, $entry) {
        $upcID = $previous->objectID;
        $toteID = $entry->objectID;
        Log::debug("upcID: $upcID, toteID: $toteID");

        // gather relevant data
        $podID = Session::get('podID');
        $articleID = Session::get('articleID');
        Log::debug("podID: $podID, articleID: $articleID");
        $tote = $this->toteRepository->find($toteID);
        $pod = $this->purchaseOrderDetailRepository->find($podID);
        $po = $this->purchaseOrderRepository->findID($pod->Order_Number);
        $upc = $this->upcRepository->find($upcID);
        $locationID = $this->findLocationID();

        // does this tote have other UPCs?
        if($this->toteHasOtherUPCs($upcID, $toteID)) {
            $responseKey = ['key' => 'putUPCinTote', 'upcID' => $upcID, 'cartonID' => $tote->Carton_ID, 'mode' => self::RECEIVE_UPC
                , 'error' => Lang::get('internal.errors.tote.containsOtherUPC', ['upcID' => $previous->Text, 'tote' => $entry->Text])
                , 'time' => Carbon::now()];
            return $responseKey;
        }

        /*
         * OK, here we must
         * 1. increment quantity on an Inventory record, (create a new one if required)
         * 2. place Inventory record in the tote, (may already be there)
         * 3. place tote onto a "work" cart, (may already be there, may need to create "work" cart)
         */
        $inventory = $this->findOrCreateInventory($upc, $pod, $tote);
        $inventory->Quantity++;
        $this->inventoryRepository->update($inventory->objectID, ['Quantity' => $inventory->Quantity]);

        $this->toteRepository->putInventoryIntoTote($inventory->objectID, $tote->objectID);
        $tote->update(['Status' => Config::get('constants.tote.status.received')]);

        $this->performanceTallyRepository->increment(['receivedUnits' => 1]);

        $podReceiving = Config::get('constants.purchaseOrderDetail.status.receiving');
        if($pod->Status != $podReceiving) {
            $this->purchaseOrderDetailRepository->update($pod->objectID, ['Status' => $podReceiving]);
        }

        $poReceiving = Config::get('constants.purchaseOrder.status.receiving');
        if($po->Status != $poReceiving) {
            $this->purchaseOrderRepository->update($po->objectID, ['Status' => $poReceiving]);
        }

        // sum up received
        $filter = [
            'POD' => $podID,
            'UPC' => $upcID,
            'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote')),
        ];
        $received = $this->receiptHistoryRepository->countOn($filter);

        // build our responseKey
        $responseKey = ['key' => 'putUPCinTote', 'upcID' => $upcID, 'upcSKU' => $upc->Client_SKU, 'cartonID' => $tote->Carton_ID
            , 'mode' => self::RECEIVE_UPC, 'time' => Carbon::now()
            , 'n' => $received, 'ofn' => $pod->Expected_Qty * $upc->Quantity];
        $activity = Lang::get('internal.receiptHistory.' . $responseKey['key'], $responseKey);

        // build our receiptHistory
        $receipt = ['PO' => $po->Purchase_Order, 'POD' => $podID, 'Article' => $articleID, 'UPC' => $upcID
            , 'Inventory' => $inventory->objectID, 'Tote' => $toteID, 'Location' => $locationID
            , 'User_Name' => Auth::user()->name, 'Activity' => $activity];
            // we don't have value for: 'Cart' => '',
        $this->receiptHistoryRepository->create($receipt);
        $responseKey['receipt'] = 'refresh';

        return $responseKey;
    }

    /**
     * find Receiving Location
     */
    protected function findLocationID() {
        $locationID = '';

        $filter = [
            'User_Name' => Auth::user()->name,
            'classID'   => Config::get('constants.userActivity.classID.ReceiveLocation'),
        ];
        $userActivity = $this->userActivityRepository->filterOn($filter, 1);
        if(isset($userActivity) && is_a($userActivity, 'vital40\UserActivity')) {
            Log::debug("vital40\\UserActivity found: ".$userActivity->Purpose);
            $locationID = $userActivity->id;
        } elseif(isset($userActivity) && is_a($userActivity, 'Illuminate\Database\Eloquent\Collection')) {
            Log::debug("Illuminate\\Database\\Eloquent\\Collection found: ".$userActivity[0]->Purpose);
            $locationID = $userActivity[0]->id;
        } elseif(isset($userActivity)) {
            Log::debug("UserActivity class: ".get_class($userActivity));
        }
        Log::debug("returning $locationID");

        return $locationID;
    }

    /**
     * Check if there are other UPCs already in this Tote.
     * Handles split and comingled Articles.
     */
    protected function toteHasOtherUPCs($upcID, $toteID) {
        $articleID = Session::get('articleID');
        Log::debug("upcID: $upcID, toteID: $toteID");
        $article = $this->articleRepository->find($articleID);

        $otherUPC = False;

        $inventoriesInTote = $this->inventoryRepository->filterOn(['THOU.container.parent' => $toteID]);
        if(isset($article->split) && $article->split == 'N') {
            // in comingling case, make sure all UPCs are from the same Article
            $upcsOfArticle = $this->upcRepository->getArticleUPCs($articleID);
            $cominglingUPCs = [];
            foreach($upcsOfArticle as $upcOfArticle) {
                $cominglingUPCs[] = $upcOfArticle->objectID;
            }
            foreach($inventoriesInTote as $invInTote) {
                if(in_array($invInTote->Item, $cominglingUPCs) == False) {
                    $otherUPC = True;
                    break;
                }
            }
        } else {
            // When Article.split, ensure tote is empty or contains only this UPC
            foreach($inventoriesInTote as $invInTote) {
                if($invInTote->Item != $upcID) {
                    $otherUPC = True;
                    break;
                }
            }
        }
        return $otherUPC;
    }

    /**
     * Check if there is an open Tote containing this UPC at this Location.
     * Handles split and comingled Articles.
     */
    protected function upcHasOpenTote($upcID) {
        $articleID = Session::get('articleID');
        Log::debug("upcID: $upcID");
        $article = $this->articleRepository->find($articleID);
        $locationID = $this->findLocationID();

        $toteID = '0';

        // Find all the Totes in this Location
        $totes = $this->toteRepository->filterOn(['THOU.Location.parent' => $locationID]);
        if(isset($totes) && count($totes) > 0) {
            foreach($totes as $tote) {
                Log::debug("Tote: $tote->Carton_ID");
                $inventoriesInTote = $this->inventoryRepository->filterOn(['THOU.container.parent' => $tote->objectID]);
                if(isset($article->split) && $article->split == 'N') {
                    // in comingling case, make sure all UPCs are from the same Article
                    $upcsOfArticle = $this->upcRepository->getArticleUPCs($articleID);
                    $cominglingUPCs = [];
                    foreach($upcsOfArticle as $upcOfArticle) {
                        $cominglingUPCs[] = $upcOfArticle->objectID;
                    }
                    foreach($inventoriesInTote as $invInTote) {
                        if(in_array($invInTote->Item, $cominglingUPCs)) {
                            $toteID = $tote->Carton_ID;
                            break 2;
                        }
                    }
                } else {
                    // When Article.split, ensure tote is empty or contains only this UPC
                    foreach($inventoriesInTote as $invInTote) {
                        if($invInTote->Item == $upcID) {
                            $toteID = $tote->Carton_ID;
                            break 2;
                        }
                    }
                }
            }
        }
        Log::debug("toteID: $toteID");

        return $toteID;
    }

    /**
     * find or create Inventory
     * desc Inventory;
    +------------+-------------+------+-----+---------+-------+
    | Field      | Type        | Null | Key | Default | Extra |
    +------------+-------------+------+-----+---------+-------+
    | objectID   | bigint(20)  | NO   | PRI | NULL    |       |
    | Item       | varchar(85) | YES  | MUL | NULL    |       |
    | Quantity   | varchar(85) | YES  |     | NULL    |       |
    | Created    | varchar(85) | YES  |     | NULL    |       |
    | Status     | varchar(85) | YES  | MUL | NULL    |       |
    | Order_Line | varchar(85) | YES  | MUL | NULL    |       |
    | UOM        | varchar(85) | YES  |     |         |       |
    +------------+-------------+------+-----+---------+-------+
    7 rows in set (0.04 sec)
     */
    protected function findOrCreateInventory($upc, $pod, $tote) {
        Log::debug("findOrCreateInventory($upc->objectID, $pod->objectID, $tote->objectID)");

        $filter = [
            'Item'             => $upc->objectID,
            'Status'           => Config::get('constants.inventory.status.received'),
            'Order_Line'       => $pod->objectID,
            'container.parent' => $tote->objectID,
        ];
        $inventory = $this->inventoryRepository->filterOn($filter, 1);

        if(isset($inventory) == false) {
            $uom = $this->uomRepository->filterOn(['Uom' => Lang::get('internal.uom.Uom.each')], 1);
            $input = [
                'Item'       => $upc->objectID,
                'Quantity'   => 0,
                'Status'     => Config::get('constants.inventory.status.received'),
                'Order_Line' => $pod->objectID,
                'UOM'        => $uom->objectID,
            ];
            $inventory = $this->inventoryRepository->create($input);
        }

        Log::debug("inventory: $inventory->objectID");
        return $inventory;
    }

    protected function closeTote($previous, $entry) {
        $toteID = $previous->objectID;
        $palletID = $entry->objectID;
        Log::debug("toteID: $toteID, palletID: $palletID");

        // gather relevant data
        $podID = Session::get('podID');
        $articleID = Session::get('articleID');
        Log::debug("podID: $podID, articleID: $articleID");
        $tote = $this->toteRepository->find($toteID);
        $pod = $this->purchaseOrderDetailRepository->find($podID);
        $po = $this->purchaseOrderRepository->findID($pod->Order_Number);
        $locationID = $this->findLocationID();
        Log::debug("locationID: $locationID");

        // Is this tote empty?
        if($this->toteRepository->isEmpty($toteID)) {
            $responseKey = ['key' => 'closeTote', 'cartonID' => $previous->Text, 'palletID' => $entry->Text
                , 'error' => Lang::get('internal.errors.tote.isEmpty', ['tote' => $previous->Text])
                , 'mode' => self::CLOSE_TOTE, 'time' => Carbon::now()];
            return $responseKey;
        }

        /*
         * At this point we have a tote, have asked receiver which cart to put it on,
         * and user has responded $palletID.
         */
        Log::debug("find Pallet: $palletID");
        $pallet = $this->palletRepository->find($palletID);

        // place this tote onto this cart
        Log::debug("putToteIntoPallet: $toteID, $palletID");
        $this->palletRepository->putToteIntoPallet($toteID, $palletID);

        /*
         * Part #2, the inventory in this tote, must change status to 'PUTAWAY'
         * - apply status change to the Tote
         * - apply status change to the Cart
         */
        Log::debug("Inventory filterOn([container.parent => $toteID])");
        $inventories = $this->inventoryRepository->filterOn(['THOU.container.parent' => $toteID]);
        foreach($inventories as $inventory) {
            $this->inventoryRepository->update($inventory->objectID, ['Status' => Config::get('constants.inventory.status.putAway')]);
        }
        $tote->update(['Status' => Config::get('constants.tote.status.putAway')]);
        //TODO do the TODO in DBPalletRepository then instate this next line
        //$pallet->update(['Status' => Config::get('constants.pallet.status.putAway')]);
        Log::debug("Pallet update([Status => putAway])");
        $this->palletRepository->update($palletID, ['Status' => Config::get('constants.pallet.status.putAway')]);

        // build our responseKey
        $responseKey = ['key' => 'closeTote', 'cartonID' => $tote->Carton_ID, 'palletID' => $pallet->Pallet_ID
            , 'mode' => self::CLOSE_TOTE, 'time' => Carbon::now()];
        $activity = Lang::get('internal.receiptHistory.' . $responseKey['key'], $responseKey);

        // build our receiptHistory
        $receipt = ['PO' => $po->Purchase_Order, 'POD' => $podID, 'Article' => $articleID
            , 'Tote' => $toteID, 'Cart' => $palletID, 'Location' => $locationID
            , 'User_Name' => Auth::user()->name, 'Activity' => $activity];
            // we don't have values for: 'UPC' => '6217092826', 'Inventory' => '6231963444',
        Log::debug("ReceiptHistory create(..): success");
        $this->receiptHistoryRepository->create($receipt);
        $responseKey['receipt'] = 'refresh';
        $responseKey['closeTote'] = 'refresh';

        // build our responseKey
        return $responseKey;
    }

    protected function scannedUPC($entry) {
        $upcID = $entry->objectID;
        Log::debug("upcID: $upcID");

        // gather relevant data
        $podID = Session::get('podID');
        $articleID = Session::get('articleID');
        Log::debug("podID: $podID, articleID: $articleID");
        $pod = $this->purchaseOrderDetailRepository->find($podID);
        $article = $this->articleRepository->find($articleID);
        //$upc = $this->upcRepository->find($upcID);
        $locationID = $this->findLocationID();

        // produce error message if $upcID not part of this pod & article
        $upcs = $this->upcRepository->getArticleUPCs($articleID, 0);
        foreach($upcs as $individual) {
            if($individual->objectID == $upcID) {
                $upc = $individual;
                break;
            }
        }
        if(!isset($upc)) {
            $responseKey = ['key' => 'scannedUPC', 'upcID' => $upcID
                , 'error' => Lang::get('internal.errors.upc.notOfArticle', ['Text' => $entry->Text, 'article' => $article->Client_SKU])
                , 'mode' => self::RECEIVE_UPC, 'time' => Carbon::now()];
            return $responseKey;
        }

        Log::debug("build our responseKey");
        // build our responseKey
        $responseKey = ['key' => 'scannedUPC', 'upcID' => $upcID, 'Description' => $upc->Description
            , 'mode' => self::RECEIVE_UPC, 'time' => Carbon::now()];

        // is there any received inventory for this UPC in this Location?
        $filter = [
            'Status'     => Config::get('constants.inventory.status.received'),
            'locationID' => $locationID,
        ];
        if(isset($article->split) and $article->split == 'N') {
            $filter['articleID']  = $article->objectID;
        } else  {
            $filter['Item']       = $upc->objectID;
            $filter['Order_Line'] = $pod->objectID;
        }
        $inventory = $this->inventoryRepository->filterOn($filter, 1);
        Log::debug("looking for inventory: ");
        Log::debug($inventory);
        if(isset($inventory)) {
            // what tote is this inventory in?
            $totes = $this->toteRepository->filterOn(['THOU.container.child' => $inventory->objectID]);
            if(isset($totes) and count($totes) > 0) {
                $responseKey['key'] = 'scannedUPCinTote';
                $responseKey['cartonID'] = $totes[0]->Carton_ID;
            }
        }

        Log::debug("return our responseKey");
        // return our responseKey
        return $responseKey;
    }

    protected function scannedTote($previous, $entry) {
        $toteID = $entry->objectID;
        Log::debug("toteID: $toteID");

        // gather relevant data
        $podID = Session::get('podID');
        $articleID = Session::get('articleID');
        Log::debug("podID: $podID, articleID: $articleID");
        $pod = $this->purchaseOrderDetailRepository->find($podID);

        // error if $toteID is not on a $pallet of this location
        $locationID = $this->findLocationID();
        Log::debug("found locationID: $locationID");
        $locationsPallet = $this->palletRepository->findOrCreate(['container.parent' => $locationID]);
        $tote = $this->toteRepository->find($toteID);
        Log::debug("looking for totesPallet of toteID: $tote->objectID");
        $totesPallet = $this->palletRepository->filterOn(['container.child' => $tote->objectID],1);
        Log::debug("totesPallet: ".(isset($totesPallet) ? $totesPallet->objectID : "not found"));
        if(!isset($totesPallet)) {
            // new tote not yet on a Pallet
            $this->palletRepository->putToteIntoPallet($tote->objectID, $locationsPallet->objectID);
            $totesPallet = $locationsPallet;
        }
        Log::debug("totesPallet: $totesPallet->objectID, locationsPallet: $locationsPallet->objectID");
        if($totesPallet->objectID != $locationsPallet->objectID) {
            $responseKey = ['key' => 'scannedTote', 'toteID' => $toteID, 'cartonID' => $tote->Carton_ID, 'Location' => $locationID
                , 'mode' => ($previous->type == self::UPC ? self::RECEIVE_UPC : self::CLOSE_TOTE), 'time' => Carbon::now()];
            if($tote->Status == Config::get('constants.tote.status.putAway')) {
                $responseKey['error'] = Lang::get('internal.errors.tote.alreadyPutAway', ['tote' => $entry->Text, 'cart' => $totesPallet->Pallet_ID]);
            } else {
                $location = $this->locationRepository->filterOn(['container.child' => $totesPallet->objectID],1);
                $responseKey['error'] = Lang::get('internal.errors.tote.anotherLocation', ['tote' => $entry->Text, 'locationID' => $location->Location_Name]);
            }
            return $responseKey;
        }

        // ask "Scan cart to put the Tote onto"
        // build our responseKey
        $responseKey = ['key' => 'scannedTote', 'toteID' => $toteID, 'cartonID' => $tote->Carton_ID
            , 'mode' => ($previous->type == self::UPC ? self::RECEIVE_UPC : self::CLOSE_TOTE), 'time' => Carbon::now()];
        if($previous->type != self::UPC) {
            $responseKey['closeTote'] = 'refresh';
        }

        // build our responseKey
        return $responseKey;
    }

    /**
     * @param $newText
     * @param $entry
     * @param $responseKey = ['Key' => 'putUPCinTote', 'upc' => $previous->objectID, 'tote' => $entry->objectID];
     * @return array
     */
    protected function buildResponse($newText, $entry, $responseKey) {
        $response = [
            'POD'         => $newText['POD'],
            'Article'     => $newText['Article'],
            'User_Name'   => $newText['User_Name'],
            'Sender_Name' => Config::get('constants.application.name'),
        ];

        Log::debug("entry");
        Log::debug(serialize($entry));
        Log::debug($responseKey);

        // calculate the mode
        if(isset($responseKey['mode'])) {
            $response['mode'] = $responseKey['mode'];
        } elseif($newText['clicked'] == 'btn-receive-upc') {
            $response['mode'] = self::RECEIVE_UPC;
        } elseif($newText['clicked'] == 'btn-close-tote') {
            $response['mode'] = self::CLOSE_TOTE;
        } else {
            $response['mode'] = self::UNKNOWN;
        }

        // if we are unable to interpret the newText, return with Duh!
        if(!isset($entry) || $entry->type == self::UNKNOWN) {
            $response[self::TEXT] = Lang::get('internal.articleFlow.unknown');
        } elseif($entry->type == self::NO_TEXT) {
            $response[self::TEXT] = Lang::get('internal.articleFlow.clicked.' . $newText['clicked'], $responseKey);
        } elseif(isset($responseKey) && isset($responseKey['error'])) {
            $response[self::TEXT] = $responseKey['error'];
        } elseif(isset($responseKey) && isset($responseKey['key'])) {
            $response[self::TEXT] = Lang::get('internal.articleFlow.' . $responseKey['key'], $responseKey);
        } else {
            $response[self::TEXT] = $entry->type . '->objectID: ' . $entry->objectID;
        }
        if(isset($responseKey) && isset($responseKey['receipt'])) {
            $response['receipt'] = $responseKey['receipt'];
        }
        if(isset($responseKey) && isset($responseKey['closeTote'])) {
            $response['closeTote'] = $responseKey['closeTote'];
        }
        return $response;
    }

}
