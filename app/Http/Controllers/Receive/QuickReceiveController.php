<?php namespace App\Http\Controllers\Receive;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use vital3\Repositories\InventoryRepositoryInterface;
use vital3\Repositories\LocationRepositoryInterface;
use vital3\Repositories\PalletRepositoryInterface;
use vital3\Repositories\VitalObjectRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\PurchaseOrderRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use vital40\Repositories\UserConversationRepositoryInterface;
use \Auth;
use \Config;
use \Entrust;
use \Input;
use \Lang;
use \Log;
use \ReceiveArticle;
use \Redirect;
use \Request;
use \Response;
use \Session;
use \View;

/**
 * Class QuickReceiveController
 * @package App\Http\Controllers
 */
class QuickReceiveController extends Controller
{

    /**
     * Reference an implementation of the Repository Interface
     * @var vital40\Repositories\PurchaseOrderDetailRepositoryInterface
     */
    protected $articleRepository;
    protected $inventoryRepository;
    protected $locationRepository;
    protected $palletRepository;
    protected $purchaseOrderDetailRepository;
    protected $purchaseOrderRepository;
    protected $receiptHistoryRepository;
    protected $upcRepository;
    protected $userActivityRepository;
    protected $toteRepository;
    protected $userConversationRepository;
    protected $vitalObjectRepository;

    /**
     * Constructor requires QuickReceive Repository
     */
    public function __construct(
          ArticleRepositoryInterface $articleRepository
        , InventoryRepositoryInterface $inventoryRepository
        , LocationRepositoryInterface $locationRepository
        , PalletRepositoryInterface $palletRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , PurchaseOrderRepositoryInterface $purchaseOrderRepository
        , ReceiptHistoryRepositoryInterface $receiptHistoryRepository
        , UPCRepositoryInterface $upcRepository
        , ToteRepositoryInterface $toteRepository
        , UserActivityRepositoryInterface $userActivityRepository
        , UserConversationRepositoryInterface $userConversationRepository
        , VitalObjectRepositoryInterface $vitalObjectRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->locationRepository = $locationRepository;
        $this->palletRepository = $palletRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->upcRepository = $upcRepository;
        $this->toteRepository = $toteRepository;
        $this->userActivityRepository = $userActivityRepository;
        $this->userConversationRepository = $userConversationRepository;
        $this->vitalObjectRepository = $vitalObjectRepository;
    }

    /**
     * Display a Listing of the resource.
     */
    public function index() {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        Log::debug((Request::ajax() ? 'Ajax Input' : 'Http Input'), Input::all());
        Log::debug('Auth User: '.Auth::user()->name);
        //$quickReceive = (object)[];
        $quickReceive = (object) Request::all();
        if(Session::has('classID')) {
            $quickReceive->classID = Session::get('classID');
        }
        //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive'));

        // repeat Button btn_Work_Table, page=2,..
        if(Session::has('classID') and Session::get('classID') == 'Location') {
            $locations = $this->locationRepository->paginate(['Location_Name' => 'Receiv', 'LocType' => 'ACTIVITY']);
            unset($quickReceive->Work_Table);
            Session::put('classID', 'Location');
        } else {
            // Do we know Location
            $filter = [
                'classID'   => Config::get('constants.userActivity.classID.ReceiveLocation'),
                'User_Name' => Auth::user()->name,
            ];
            $userActivity = $this->userActivityRepository->filterOn($filter, $limit = 1);
            if(isset($userActivity)) {
                $location = $this->locationRepository->find($userActivity->id);
                if(isset($location)) {
                    $this->setLocation($quickReceive, $location);
                }
            }
        }

        // repeat Button btn_Purchase_Order, page=2,..
        if(Request::has('btn_Purchase_Order')) {
            $purchaseOrder['btn_Purchase_Order'] = 'repeat button Purchase_Order';
            $filter = [
                'Created' => 'last 120 days',
                'Status'  => [Config::get('constants.purchaseOrder.status.receiving'),Config::get('constants.purchaseOrder.status.open')],
            ];
            $purchaseOrders = $this->purchaseOrderRepository->paginate($filter);
            unset($quickReceive->Purchase_Order);
            Session::put('classID', 'Purchase_Order');
        } else {
            $purchaseOrder=[];

            // Do we know Purchase Order
            $filter = [
                'classID'   => Config::get('constants.userActivity.classID.ReceivePO'),
                'User_Name' => Auth::user()->name,
            ];
            $userActivity = $this->userActivityRepository->filterOn($filter, $limit = 1);
            if(isset($userActivity)) {
                $purchaseOrder = $this->purchaseOrderRepository->find($userActivity->id);
                if(isset($purchaseOrder)) {
                    //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'userActivity', 'purchaseOrder'));
                    $this->setPurchaseOrder($quickReceive, $purchaseOrder);
                }
            }
        }

        //dd(__METHOD__."(".__LINE__.")",compact('quickReceive','errors'));
        // repeat Button btn_Article, page=2,..
        if(Request::has('btn_Article')) {
            $receiveArticle['btn_Article'] = 'repeat button Article';
            $receiveArticles = $this->mergePODetailWithArticle(['Order_Number' => $quickReceive->Purchase_Order]);
            unset($quickReceive->Article);
            Session::forget('podID');
            Session::put('classID', 'Article');
        } else {
            $receiveArticle=[];

            // Do we know Article
            $filter = [
                'classID'   => Config::get('constants.userActivity.classID.ReceiveArticle'),
                'User_Name' => Auth::user()->name,
            ];
            $userActivity = $this->userActivityRepository->filterOn($filter, $limit = 1);
            if(isset($userActivity)) {
                $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($userActivity->id);
                if(isset($purchaseOrderDetail)) {
                    $this->setArticle($quickReceive, $purchaseOrderDetail);
                }

                if(Session::has('articleID')) {
                    $reworks = Lang::get('lists.article.rework');
                }
            }
        }

        // retrieve current open tote upc quantities
        if(isset($purchaseOrderDetail)) {
            $upcGridLines = $this->calculateUPCGridLines($purchaseOrderDetail->objectID);
        }

        $filter = [];
        if(isset($quickReceive->Article) && strlen($quickReceive->Article) > 0) {
            $filter['articleID'] = $quickReceive->Article;
        }
        if(isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0) {
            $filter['upcID'] = $quickReceive->UPC;
        }
        if(count($filter)) {
            Log::debug("calling calculatePickFaceLines",$filter);
            $pickFaceLines = $this->calculatePickFaceLines($filter);
            //$sessionData = Session::all();
            //$user = Auth::user();
            //$uin = $this->uin();
            //dd($uin.__METHOD__."(".__LINE__.")",compact('sessionData','user','quickReceive', 'locations', 'purchaseOrder', 'receiveArticle', 'upcGridLines', 'pickFaceLines'));
        }

        // retrieve current UserConversation
        $userConversations = $this->getUserConversations();

        // Using the view(..) helper function
        return view('pages.quickReceive.index', compact('quickReceive', 'locations', 'purchaseOrder', 'purchaseOrders', 'receiveArticle', 'receiveArticles', 'reworks', 'upcGridLines', 'pickFaceLines', 'userConversations'));
    }

    /**
     * Display a Filtered Listing of the resource.
     */
    public function filter() {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        //check if its our form
        if( Session::token() !== Input::get( '_token' ) ) {
            Log::error('token: '.Session::token());
            Log::error('_token: '.Input::get( '_token' ));
            if(Request::ajax())
                return Response::json(['msg' => 'Unauthorized attempt to refresh texting area']);
            else
                return redirect()->route('home');
        }

        Log::debug((Request::ajax() ? 'Ajax Input' : 'Http Input'), Input::all());

        $quickReceive = (object) Request::all();
        //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive'));
        $errors = [];

        // Button btn_Leave_Quick_Receiving
        if(isset($quickReceive->btn_leave)) {
            $this->userActivityRepository->dissociate();

            // record a text line
            $textLine = [
                'User_Name'   => Auth::user()->name,
                'Sender_Name' => Auth::user()->name,
                'Text'        => Lang::get('internal.articleFlow.leaveReceiving'),
            ];
            if(Session::has('podID'))
                $textLine['POD'] = Session::get('podID');
            if(Session::has('articleID'))
                $textLine['Article'] = Session::get('articleID');
            $this->userConversationRepository->create($textLine);

            Session::forget('alreadyInUse');
            Session::forget('articleID');
            Session::forget('classID');
            Session::forget('locationID');
            Session::forget('podID');
            Session::forget('poID');

            return redirect()->route('home');
        }

        // Button Override AlreadyInUse
        if(isset($quickReceive->btn_alreadyInUse)) {
            $name = Session::get('alreadyInUse');
            // What was she doing?
            $userActivities = $this->userActivityRepository->filterOn(['User_Name' => $name]);
            // Release her
            $this->userActivityRepository->dissociate($name);

            // record a text line
            $textLine = [
                'User_Name'   => $name,
                'Sender_Name' => Auth::user()->name,
                'Text'        => Lang::get('internal.articleFlow.forcedOutOfReceiving',['super' => Auth::user()->name]),
            ];
            // based on what she was doing
            foreach($userActivities as $userActivity) {
                if($userActivity->classID == Config::get('constants.userActivity.classID.ReceiveArticle')) {
                    $textLine['POD'] = $userActivity->id;
                    $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($userActivity->id);
                    $textLine['Article'] = $purchaseOrderDetail->SKU;
                    break;
                }
            }
            $this->userConversationRepository->create($textLine);

            Session::forget('alreadyInUse');
            return redirect()->route('home');
        }
        Session::forget('alreadyInUse');

        // Button btn_Work_Table
        if(isset($quickReceive->btn_Work_Table)) {
            $locations = $this->locationRepository->paginate(['Location_Name' => 'Receiv', 'LocType' => 'ACTIVITY']);
            unset($quickReceive->Work_Table);
            Session::put('classID', 'Location');
        }

        // Button btn_Purchase_Order
        if(isset($quickReceive->btn_Purchase_Order)) {
            $purchaseOrder['btn_Purchase_Order'] = 'repeat button Purchase_Order';
            $filter = [
                'Created' => 'last 120 days',
                'Status'  => [Config::get('constants.purchaseOrder.status.receiving'),Config::get('constants.purchaseOrder.status.open')],
            ];
            $purchaseOrders = $this->purchaseOrderRepository->paginate($filter);
            unset($quickReceive->Purchase_Order);
            Session::put('classID', 'Purchase_Order');
        }

        //dd(__METHOD__."(".__LINE__.")",compact('quickReceive','errors'));
        // Button btn_Article
        if(isset($quickReceive->btn_Article)) {
            $receiveArticle['btn_Article'] = 'repeat button Article';
            $receiveArticles = $this->mergePODetailWithArticle(['Order_Number' => $quickReceive->Purchase_Order]);
            unset($quickReceive->Article);
            Session::forget('podID');
            Session::put('classID', 'Article');
        } else {
            $receiveArticle=[];
            $reworks = Lang::get('lists.article.rework');
        }

        // user hit enter
        if(isset($quickReceive->btn_enter)) {
            // Validate Location
            $this->validateWorkTable($quickReceive, $errors);

            $this->validatePurchaseOrder($quickReceive, $errors);

            $this->validateArticle($quickReceive, $errors);
        }

        if(count($errors) == 0) {
            Log::debug("has podID: ".(Session::has('podID') ? Session::get('podID') : 'unknown').
                ", has Location: ".(Session::has('locationID') ? Session::get('locationID') : 'unknown'));
            // Add Location to PurchaseOrderDetail
            if(Session::has('locationID')) {
                $locationID = Session::get('locationID');

                if(Session::has('podID')) {
                    $quickReceive->podLocation = $locationID;
                    ReceiveArticle::setPurchaseOrderDetailLocation(Session::get('podID'), $locationID);
                }
            }

            // When multiple PODs for this UPC
            if(   (isset($quickReceive->Purchase_Order))
              and (isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0)
              and (isset($quickReceive->Article) == False || strlen($quickReceive->Article) == 0)) {
                // emulate the btn_Article was pressed and we set filter to list the multiple PODs for this UPC
                $receiveArticle['btn_Article'] = 'repeat button Article';
                $receiveArticles = $this->mergePODetailWithArticle(['Order_Number' => $quickReceive->Purchase_Order, 'contains.UPC' => $quickReceive->UPC]);
                unset($quickReceive->Article);
                unset($reworks);
                unset($quickReceive->Rework);
                Session::forget('podID');
                Session::put('classID', 'Article');
            }

            Log::debug("articleID: ".(Session::has('articleID') ? Session::get('articleID') : 'unknown').
                ", receiveArticles: ".(isset($receiveArticles) && count($receiveArticles) ? count($receiveArticles) : 'none').
                ", Rework: ".(Input::has('Rework') ? Input::get('Rework') : 'unknown'));
            // if we need to, update rework value
            if(Session::has('articleID') && Input::has('Rework') && Input::get('Rework') != '0') {
                $this->setRework($quickReceive);
            }
        } else {
            unset($quickReceive->Rework);
        }

        // retrieve current open tote upc quantities
        Log::debug("location: ".(Session::has('locationID') ? Session::get('locationID') : 'not set').
            ", purchaseOrderDetail: ".(Session::has('podID') ? Session::get('podID') : 'not set'));
        $upcGridLines = $this->calculateUPCGridLines(Session::get('podID'));

        $filter = [];
        if(isset($quickReceive->Article) && strlen($quickReceive->Article) > 0) {
            $filter['articleID'] = $quickReceive->Article;
        }
        if(isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0) {
            $filter['upcID'] = $quickReceive->UPC;
        }
        if(count($filter)) {
            Log::debug("calling calculatePickFaceLines",$filter);
            $pickFaceLines = $this->calculatePickFaceLines($filter);
        }

        // retrieve current UserConversation
        $userConversations = $this->getUserConversations();

        // prepare response parameters
        $responseParams = compact('quickReceive', 'locations', 'purchaseOrder', 'purchaseOrders', 'receiveArticle', 'receiveArticles', 'reworks', 'upcGridLines', 'pickFaceLines', 'userConversations');
        if(Request::ajax()) return Response::json($responseParams);
        if(count($errors) > 0) return Redirect::back()->withErrors($errors)->withInput();
        return View::make('pages.quickReceive.index', $responseParams);
    }

    public function uin() {
        $user = Auth::user();
        return $user->id.'.'.$user->name.' - ';
    }

    /**
     * Display a specific resource
     * In this case, the $id may be one of several objects (Location, Purchase_Order, Article, ..).
     */
    public function show($id) {
        if(Entrust::can('receive') == false) return redirect()->route('home');
        Session::forget('alreadyInUse');

        $errors=[];

        $quickReceive = (object) Request::all();
        Log::debug('show('.$id.')');

        // what object has this $id
        if(Session::has('classID')) {
            $classID = Session::get('classID');
        } else {
            $voObject = $this->vitalObjectRepository->find($id);
            if(isset($voObject->classID))
                $classID = $voObject->classID;
        }

        Log::debug('classID: '.(isset($classID) ? $classID : 'not set'));
        // re-establish what the session already knows
        if(!isset($classID) or $classID != 'Location')
            if(Session::has('locationID')) {
                $location = $this->locationRepository->find(Session::get('locationID'));
                if(isset($location))
                    $quickReceive->Work_Table = $location->Location_Name;
            }
        if(!isset($classID) or $classID != 'Purchase_Order')
            if(Session::has('poID')) {
                $purchaseOrder = $this->purchaseOrderRepository->find(Session::get('poID'));
                if(isset($purchaseOrder))
                    $quickReceive->Purchase_Order = $purchaseOrder->Purchase_Order;
            }
        if(!isset($classID) or ($classID != 'Article' and $classID != 'Inbound_Order_Detail'))
            if(Session::has('articleID')) {
                $article = $this->articleRepository->find(Session::get('articleID'));
                if(isset($article))
                    $quickReceive->Article = $article->Client_SKU;
            }

        // process $id
        if(isset($classID) && strlen($classID) > 0) {
            if($classID == 'Location') {
                Log::debug('Work_Table selected: '.$id);
                // Validate Location
                $quickReceive->Work_Table = $id;

            } elseif($classID == 'Purchase_Order') {
                Log::debug('Purchase_Order selected: '.$id);
                $purchaseOrder = $this->purchaseOrderRepository->find($id);
                $quickReceive->Purchase_Order = $purchaseOrder->Purchase_Order;

            } elseif($classID == 'Article' or $classID == 'Inbound_Order_Detail') {
                Log::debug('Article selected: '.$id);
                // Validate Article
                $quickReceive->podID = $id;
                $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($id);
                $article = $this->articleRepository->find($purchaseOrderDetail->SKU);
                $quickReceive->Article = $article->Client_SKU;

            }
        } else {
            // test the $id, may be a Purchase_Order number
            $purchaseOrder = $this->purchaseOrderRepository->filterOn(['Purchase_Order' => $id],1);
            if(isset($purchaseOrder)) {
                Log::debug('Purchase_Order selected: '.$id);
                $quickReceive->Purchase_Order = $purchaseOrder->Purchase_Order;
            }
        }

        // Validate Location
        $this->validateWorkTable($quickReceive, $errors);

        $this->validatePurchaseOrder($quickReceive, $errors);

        $this->validateArticle($quickReceive, $errors);

        if(count($errors) == 0) {
            Log::debug("has podID: ".(Session::has('podID') ? Session::get('podID') : 'unknown').
                ", has Location: ".(Session::has('locationID') ? Session::get('locationID') : 'unknown'));
            // Add Location to PurchaseOrderDetail
            if(Session::has('locationID')) {
                $locationID = Session::get('locationID');

                if(Session::has('podID')) {
                    $quickReceive->podLocation = $locationID;
                    ReceiveArticle::setPurchaseOrderDetailLocation(Session::get('podID'), $locationID);
                }
            }

            // When multiple PODs for this UPC
            if(   (isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0)
                and (isset($quickReceive->Article) == False || strlen($quickReceive->Article) == 0)) {
                // emulate the btn_Article was pressed and we set filter to list the multiple PODs for this UPC
                $receiveArticle['btn_Article'] = 'repeat button Article';
                $receiveArticles = $this->mergePODetailWithArticle(['Order_Number' => $quickReceive->Purchase_Order, 'contains.UPC' => $quickReceive->UPC]);
                unset($quickReceive->Article);
                unset($reworks);
                unset($quickReceive->Rework);
                Session::forget('podID');
                Session::put('classID', 'Article');
            }

            Log::debug("has articleID: ".(Session::has('articleID') ? Session::get('articleID') : 'unknown').
                ", has Rework: ".(Input::has('Rework') ? Input::get('Rework') : 'unknown'));
            // if we need to, update rework value
            if(Session::has('articleID') && Input::has('Rework') && Input::get('Rework') != '0') {
                $this->setRework($quickReceive);
            }
        } else {
            unset($quickReceive->Rework);
        }

        if(Session::has('articleID')) {
            $reworks = Lang::get('lists.article.rework');
        }

        // retrieve current open tote upc quantities
        Log::debug("location: ".(Session::has('locationID') ? Session::get('locationID') : 'not set').
            ", purchaseOrderDetail: ".(Session::has('podID') ? Session::get('podID') : 'not set'));
        $upcGridLines = $this->calculateUPCGridLines(Session::get('podID'));

        $filter = [];
        if(isset($quickReceive->Article) && strlen($quickReceive->Article) > 0) {
            $filter['articleID'] = $quickReceive->Article;
        }
        if(isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0) {
            $filter['upcID'] = $quickReceive->UPC;
        }
        if(count($filter)) {
            Log::debug("calling calculatePickFaceLines",$filter);
            $pickFaceLines = $this->calculatePickFaceLines($filter);
        }

        // retrieve current UserConversation
        $userConversations = $this->getUserConversations();

        // prepare response parameters
        $responseParams = compact('quickReceive', 'reworks', 'upcGridLines', 'pickFaceLines', 'userConversations');
        if(Request::ajax()) return Response::json($responseParams);
        if(count($errors) > 0) return Redirect::back()->withErrors($errors)->withInput();
        return View::make('pages.quickReceive.index', $responseParams);
    }

    /**
     * ajax refresh the UPCs grid lines
     */
    public function upcGridLines() {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        //check if its our form
        if( Session::token() !== Input::get( '_token' ) ) {
            return Response::json(['msg' => 'Unauthorized attempt to refresh the UPC grid']);
        }

        Log::debug((Request::ajax() ? 'Ajax Input' : 'Http Input'), Input::all());

        $locationID = Session::get('locationID');
        $podID = Session::get('podID');

        // retrieve current open tote upc quantities
        $upcGridLines = $this->calculateUPCGridLines($podID);

        $jsonResponse = Response::json( compact('upcGridLines') );
        //Log::debug(var_dump($jsonResponse));
        Log::debug($jsonResponse);

        return $jsonResponse;
    }

    /**
     * Calculate the UPCs grid lines
     */
    protected function calculateUPCGridLines($podID) {
        $results = [];
        $locationID = Session::get('locationID');
        Log::debug('LocationID: '.$locationID);
        if(isset($locationID) && isset($podID)) {
            $pod = $this->purchaseOrderDetailRepository->find($podID);
            Log::debug('podID: '.$podID.',  pod: '.$pod->SKU);
        }
        if(isset($pod)) {
            $article = $this->articleRepository->find($pod->SKU);
            Log::debug('Article: '.$article->Client_SKU);
        }
        if(isset($article)) {
            $upcs = $this->upcRepository->getArticleUPCs($article->objectID, 0);
            Log::debug('Article: '.$article->objectID.',  UPCs: '.count($upcs));
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('locationID', 'podID', 'pod', 'article', 'upcs'));

        if(isset($upcs)) {
            $keyNum = 0;
            $res = [];
            foreach($upcs as $upc) {
                // filter to count received
                $filter = [
                    'POD' => $podID,
                    'UPC' => $upc->objectID,
                    'Activity' => preg_replace('/ - .*/', '', Lang::get('internal.receiptHistory.putUPCinTote')),
                ];
                $receiptHistories = $this->receiptHistoryRepository->filterOn($filter,0);
                $received = count($receiptHistories);
                $expected = $upc->parents[$article->objectID]->Quantity * $pod->Expected_Qty;
                $varVal = abs($received - $expected);
                $varStr = ($expected == $received ? '' : ($expected < $received ? 'over' : 'short'));
                $totes = '';
                if($received > 0) {
                    if(Entrust::hasRole('support')) {
                        $toteIDs = [];
                        $separator = '';
                        foreach($receiptHistories as $receiptHistory) {
                            if(isset($receiptHistory->Tote) and array_key_exists($receiptHistory->Tote,$toteIDs) == false) {
                                $toteIDs[$receiptHistory->Tote] = 'used';
                                //TODO - should refer to the Carton_ID on receiptHistory, add this field
                                $tote = $this->toteRepository->filterOn(['objectID' => $receiptHistory->Tote], 1);
                                if(isset($tote)) {
                                    $totes = $totes . $separator . preg_replace('/ /', '&nbsp;', $tote->Carton_ID);
                                    $separator = ', ';
                                }
                            }
                        }
                    } else {
                        $filter = [
                            'locationID' => $locationID,
                            'Status'     => 'RECD',
                            'upcID'      => $upc->objectID,
                        ];
                        $separator = '';
                        $toteData = $this->toteRepository->filterOn($filter);
                        Log::debug('toteData: ');
                        Log::debug($toteData);
                        foreach($toteData as $tote) {
                            if(isset($tote)) {
                                $totes = $totes . $separator . preg_replace('/ /', '&nbsp;', $tote->Carton_ID);
                                $separator = ', ';
                            }
                        }
                    }
                }
                $keyGroup = ($expected == $received ? 1000 : 0);
                $keyNum++;
                /*
                 * UPC grid;
                +----------------------+-------------+------+-----+---------+-------+
                | Field                | Type        | Null | Key | Default | Extra |
                +----------------------+-------------+------+-----+---------+-------+
                | upcID                | big int(20) | YES  |     | 0       |       |
                | Client_SKU           | varchar(85) | YES  |     | NULL    |       |
                | Description          | varchar(85) | YES  |     | NULL    |       |
                | Expected             | big int(20) | YES  |     | 0       |       |
                | Received             | big int(20) | YES  |     | 0       |       |
                | Variance             | varchar(85) | YES  |     | NULL    |       |
                | Totes                | varchar(85) | YES  |     | NULL    |       |
                +----------------------+-------------+------+-----+---------+-------+
                 */
                $result = (object)[
                    'upcID'       => $upc->objectID,
                    'Client_SKU'  => $upc->Client_SKU,
                    'Description' => $upc->Description,
                    'Expected'    => $expected,
                    'Received'    => $received,
                    'Variance'    => "$varVal $varStr",
                    'Totes'       => $totes,
                ];
                $res[($keyGroup+$keyNum)] = $result;
            }

            // sort the completed SKUs to the bottom
            ksort($res);
            foreach($res as $key => $value) {
                $results[] = $value;
            }
        }
        //if(isset($locationID) and isset($podID)) {
        //    return $this->toteRepository->openToteContents($locationID, $podID);
        //}
        return $results;
    }

    /**
     * ajax refresh the pick face lines
     */
    public function pickFaceLines() {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        //check if its our form
        if( Session::token() !== Input::get( '_token' ) ) {
            return Response::json(['msg' => 'Unauthorized attempt to refresh the pick face lines']);
        }

        Log::debug((Request::ajax() ? 'Ajax Input' : 'Http Input'), Input::all());

        // retrieve current open tote upc quantities
        $filter = [];
        if(Session::has('toteID') && strlen(Session::get('toteID')) > 0) {
            $filter['toteID'] = Session::get('toteID');
        }
        if(Session::has('articleID') && strlen(Session::get('articleID')) > 0) {
            $filter['articleID'] = Session::get('articleID');
        }
        if(count($filter)) {
            Log::debug("calling calculatePickFaceLines",$filter);
            $pickFaceLines = $this->calculatePickFaceLines($filter);
        }

        $jsonResponse = Response::json( compact('pickFaceLines') );
        //Log::debug(var_dump($jsonResponse));
        Log::debug($jsonResponse);
        return $jsonResponse;
    }

    /**
     * List forward pick face, activity and reserve locations with quantity for this UPC.
     */
    protected function calculatePickFaceLines($filter) {
        Log::debug('filter: ',$filter);
        //dd(__METHOD__.'('.__LINE__.')',compact('filter'));

        $upcs = $this->upcRepository->combine($filter);
        //dd(__METHOD__.'('.__LINE__.')',compact('filter','upcs'));

        Log::debug('upcs: '.count($upcs));
        /*
         * Ok, so what Inventory do we have of our list of UPCs?
         * 1. if UPC exists in a pick face, display pick face location, & inventory level
         * 2. if UPC exists has active replen, show active replen quantity
         * 3. if UPC in reserve, display reserve location and quantity
         */
        $results = [];
        $onHandInventories = $this->inventoryRepository->onHandReport($upcs);
        foreach($onHandInventories as $onHandInventory) {
            if($onHandInventory->Status == 'OPEN' and $onHandInventory->LocType == 'Reserve') {
                // we are not interested in OPEN Reserve Inventory
            } else {
                $onHandInventory->Carton_ID = preg_replace('/ /', '&nbsp;', $onHandInventory->Carton_ID);
                $results[] = $onHandInventory;
            }
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('filter','upcs','onHandInventories','results'));

        Log::debug('results: ',$results);
        return $results;
    }

    /**
     * ajax refresh the texting area
     */
    public function texting() {
        // TODO - Add ENTRUST authorization check

        //check if its our form
        if( Session::token() !== Input::get( '_token' ) ) {
            return Response::json(['msg' => 'Unauthorized attempt to refresh texting area']);
        }

        Log::debug((Request::ajax() ? 'Ajax Input' : 'Http Input'), Input::all());

        $podID = Session::get('podID');
        $articleID = Session::get('articleID');
        $text_entry = Input::get( 'text_entry' );
        $clicked = Input::get('clicked');
        if(isset($text_entry) == false && ($clicked == "btn-receive-upc" || $clicked == "btn-close-tote"))
            $text_entry = "";
        Log::debug("podID: $podID, articleID: $articleID");

        if(isset($podID) == False or isset($articleID) == False) {
            $responseText = [
                'POD'         => (isset($podID) ? $podID : 0),
                'Article'     => (isset($articleID) ? $articleID : 0),
                'User_Name'   => Auth::user()->name,
                'Sender_Name' => Config::get('constants.application.name'),
                'Text'        => Lang::get('internal.errors.article.noArticle'),
                'mode'        => 'unknown',
            ];
        } else {
            // record the new text_entry
            $newText = [
                'POD' => $podID,
                'Article' => $articleID,
                'User_Name' => Auth::user()->name,
                'Sender_Name' => Auth::user()->name,
                'Text' => $text_entry,
                'clicked' => $clicked,
            ];
            Log::debug('newText:', $newText);
            $this->userConversationRepository->create($newText);

            // calling into business logic
            $responseText = ReceiveArticle::textEntry($newText);
            Log::debug('responseText:', $responseText);
        }

        $this->userConversationRepository->create($responseText);

        // current UserConversation
        $userConversations = $this->getUserConversations();

        $jsonResponse = Response::json( compact('userConversations', 'responseText') );
        Log::debug($jsonResponse);

        return $jsonResponse;
    }

    /**
     * @param $quickReceive
     * @return array $result['verdict'] = 'valid' | 'not entered' | 'error'
     */
    protected function validateWorkTable(&$quickReceive, &$errors) {
        Log::debug('quickReceive->'
            . ' Work_Table: '.(isset($quickReceive->Work_Table) ? $quickReceive->Work_Table : '-' )
            .', Purchase_Order: '.(isset($quickReceive->Purchase_Order) ? $quickReceive->Purchase_Order : '-' )
            .', podID: '.(isset($quickReceive->podID) ? $quickReceive->podID : '-' )
            .', Article: '.(isset($quickReceive->Article) ? $quickReceive->Article : '-' )
            .', UPC: '.(isset($quickReceive->UPC) ? $quickReceive->UPC : '-' )
            .', Rework: '.(isset($quickReceive->Rework) ? $quickReceive->Rework : '-' ));
        //Log::debug(var_dump($quickReceive));

        unset($quickReceive->podLocation);
        $result = [];

        // was Location / Work_Table entered?
        if(isset($quickReceive->Work_Table) && strlen($quickReceive->Work_Table) > 4) {
            $goodSoFar = True;
        } else {
            $goodSoFar = False;
            $result['verdict'] = 'not entered';
        }

        // is entered value a valid objectID or Location_Name, can one location be found
        if($goodSoFar) {
            $locations = $this->locationRepository->filterOn(['objectID or Location_Name' => $quickReceive->Work_Table],2);
            //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'locations'));
            if(isset($locations) && count($locations) == 1) {
                $location = $locations[0];
                $quickReceive->Work_Table = $location->Location_Name;
                $goodSoFar = True;
            } else {
                $goodSoFar = False;
                $result['verdict'] = 'error';
                // Location not found!
                $errors['Work_Table'][] = Lang::get('internal.errors.location.notValidLocation', ['Work_Table' => $quickReceive->Work_Table]);
            }
            unset($locations);
        }

        // is someone else tied to this location?
        if($goodSoFar) {
            $userActivities = $this->userActivityRepository->getUserActivities($location->objectID, Config::get('constants.userActivity.classID.ReceiveLocation'));
            //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'location', 'userActivities'));
            if(isset($userActivities) && count($userActivities) > 0 && $userActivities[0]->User_Name != Auth::user()->name) {
                $goodSoFar = False;
                $result['verdict'] = 'error';
                // Location Already in use!
                $errors['Work_Table'][] = Lang::get('internal.errors.location.alreadyInUse', ['Work_Table' => $quickReceive->Work_Table, 'User_Name' => $userActivities[0]->User_Name]);
                Session::put('alreadyInUse', $userActivities[0]->User_Name);
            }
            unset($userActivities);
        }

        /*
         * Here we attempt to provide a Location based on entered Article, UPC
         *  - Are we able to find a Location from the Article, UPC entered
         *  - Checking if the Location is currently in use by someone else
         *  - Checking for open totes in the location, can we use this location?
         */
        $filter = [
            'Status' => [Config::get('constants.purchaseOrderDetail.status.receiving'),Config::get('constants.purchaseOrderDetail.status.open')],
        ];
        if(isset($quickReceive->Article) && strlen($quickReceive->Article) > 0) {
            $filter['item.Client_SKU'] = $quickReceive->Article;
        }
        if(isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0) {
            $filter['item.UPC'] = $quickReceive->UPC;
        }
        //Log::debug($filter);
        if(count($filter) > 1) {
            $purchaseOrderDetails = $this->purchaseOrderDetailRepository->filterOn($filter,2);
            if(count($purchaseOrderDetails) == 1 && isset($purchaseOrderDetails[0]->Location) && strlen($purchaseOrderDetails[0]->Location) > 3) {
                $podLocation = $this->locationRepository->find($purchaseOrderDetails[0]->Location);
                if(isset($podLocation)) {
                    //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetails', 'podLocation', 'result'));
                    if(!isset($quickReceive->Work_Table) || strlen($quickReceive->Work_Table) == 0) {
                        Log::debug("Work_Table: null, poda has location: $podLocation->Location_Name");

                        // Location was not entered, but we found it using Article and/or UPC
                        $goodSoFar = True;
                        $quickReceive->Work_Table = $podLocation->Location_Name;
                        $location = $podLocation;

                        // when purchaseOrderDetail->location points to a work table that is currently occupied
                        $userActivities = $this->userActivityRepository->getUserActivities($podLocation->objectID, Config::get('constants.userActivity.classID.ReceiveLocation'));
                        if(isset($userActivities) && count($userActivities) > 0 && $userActivities[0]->User_Name != Auth::user()->name) {
                            // Location Already in use!
                            $this->purchaseOrderDetailRepository->update($purchaseOrderDetails[0]->objectID, ['Location' => 0]);
                            $goodSoFar = False;
                            $result['verdict'] = 'error';
                            $errors['Work_Table'][] = Lang::get('internal.errors.location.alreadyInUse', ['Work_Table' => $quickReceive->Work_Table, 'User_Name' => $userActivities[0]->User_Name]);
                            //$quickReceive->Work_Table = '';
                        }
                        unset($userActivities);
                    }
                    if($goodSoFar && $quickReceive->Work_Table != $podLocation->Location_Name) {
                        Log::warning("Work_Table: $quickReceive->Work_Table, != poda has location: $podLocation->Location_Name");

                        // If there are open totes in purchaseOrderDetail->location, error message "Open totes for this Article or UPC" ..
                        $totes = $this->toteRepository->filterOn(['THOU.locID_and_podID' => [$purchaseOrderDetails[0]->Location, $purchaseOrderDetails[0]->objectID]],2);
                        //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetail', 'location', 'podLocation', 'totes'));
                        if(isset($totes) && count($totes) > 0) {
                            $goodSoFar = False;
                            $result['verdict'] = 'error';
                            $errors['Work_Table'][] = Lang::get('internal.errors.location.alreadyOpen', [
                                'Work_Table' => $quickReceive->Work_Table, 'Location_Name' => $podLocation->Location_Name
                            ]);
                        }

                        // If there are open totes in $quickReceive->Work_Table, error message "Open totes for this Article or UPC" ..
                        $totes = $this->toteRepository->filterOn(['THOU.locID_not_podID' => [$location->objectID, $purchaseOrderDetails[0]->objectID]],2);
                        //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetail', 'location', 'podLocation', 'totes'));
                        if(isset($totes) && count($totes) > 0) {
                            $goodSoFar = False;
                            $result['verdict'] = 'error';
                            $errors['Work_Table'][] = Lang::get('internal.errors.location.hasOpen', [
                                'Work_Table' => $quickReceive->Work_Table, 'Location_Name' => $location->Location_Name
                            ]);
                        }

                        // If there are no open totes we can switch to that location.
                        if($goodSoFar) {
                            $quickReceive->Work_Table = $location->Location_Name;
                        }
                    }
                }
            }
        }

        if($goodSoFar) {
            $result['verdict'] = 'valid';
            $this->setLocation($quickReceive, $location);
        } elseif($result['verdict'] == 'error') {
            Session::forget('locationID');
            unset($quickReceive->Rework);
        }
        Log::debug("result[verdict]: ".$result['verdict']);

        return $result;
    }

    protected function setLocation(&$quickReceive, $location) {
        // set Session variables
        Session::put('locationID', $location->objectID);
        $quickReceive->Work_Table = $location->Location_Name;
        Log::debug("Work_Table: $quickReceive->Work_Table");

        //$requestAll = Request::all();
        //$sessionAll = Session::all();
        //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'location', 'requestAll', 'sessionAll'));

        // record a text line
        $textLine = [
            'User_Name'   => Auth::user()->name,
            'Sender_Name' => Config::get('constants.application.name'),
            'Text'        => Lang::get('internal.articleFlow.selectLocation', ['Work_Table' => $quickReceive->Work_Table]),
        ];
        if(Session::has('podID'))
            $textLine['POD'] = Session::get('podID');
        if(Session::has('articleID'))
            $textLine['Article'] = Session::get('articleID');
        $this->userConversationRepository->create($textLine);

        // if user pressed [Select a Location] button to get here, then we are finished with it
        if(Session::has('classID') && Session::get('classID') == 'Location')
            Session::forget('classID');

        // calling into business logic
        ReceiveArticle::selectLocation($location);
    }

    protected function validatePurchaseOrder(&$quickReceive, &$errors) {
        $goodSoFar = False;
        $result['PO'] = 'not entered';
        Log::debug(__METHOD__.'('.__LINE__.'):');

        if(isset($quickReceive->Purchase_Order) and strlen($quickReceive->Purchase_Order) > 0) {
            $result['PO'] = 'entered';
            // Entered Purchase_Order, check it out
            $filter = [
                'Status'         => [Config::get('constants.purchaseOrder.status.receiving'),Config::get('constants.purchaseOrder.status.open')],
                'Purchase_Order' => $quickReceive->Purchase_Order,
            ];
            $purchaseOrders = $this->purchaseOrderRepository->filterOn($filter, 2);
            if(count($purchaseOrders) == 1) {
                $goodSoFar = True;
                $purchaseOrder = $purchaseOrders[0];
                Log::debug('entered, found: '.$purchaseOrder->Purchase_Order);
            } else {
                $goodSoFar = False;
                $result['verdict'] = 'error';
                $errors['Purchase_Order'][] = Lang::get('internal.errors.purchaseOrder.notOpen', ['Purchase_Order' => $quickReceive->Purchase_Order]);
            }
        }

        if($result['PO'] == 'not entered') {
            // Entered UPC, or Article may imply a single Purchase_Order
            $filter = [
                'Status' => [Config::get('constants.purchaseOrder.status.receiving'),Config::get('constants.purchaseOrder.status.open')],
            ];
            if(isset($quickReceive->Article) && strlen($quickReceive->Article) > 0) {
                $filter['item.Sku_Number'] = $quickReceive->Article;
            }
            if(isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0) {
                $filter['item.UPC'] = $quickReceive->UPC;
            }
            Log::debug($filter);
            $purchaseOrders = $this->purchaseOrderRepository->filterOn($filter,2);
            if(count($purchaseOrders) == 1) {
                $goodSoFar = True;
                $purchaseOrder = $purchaseOrders[0];
                $result['PO'] == 'implied by UPC or Article';
                Log::debug('implied by UPC or Article: '.$purchaseOrder->Purchase_Order);
            } elseif(count($purchaseOrders) == 0) {
                if(count($filter) > 1) {
                    $params=[];
                    if(isset($quickReceive->Article) && strlen($quickReceive->Article) > 0)
                        $params['Article'] = $quickReceive->Article;
                    if(isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0)
                        $params['UPC'] = $quickReceive->UPC;
                    $goodSoFar = False;
                    $result['verdict'] = 'error';
                    $errors['Purchase_Order'][] = Lang::get('internal.errors.purchaseOrder.noOpenPOs', $params);
                    //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetails', 'errors','result'));
                }
            }
        }
        unset($purchaseOrders);

        if($result['PO'] == 'not entered') {
            Log::debug("Session locationID ".(Session::has('locationID') ? Session::get('locationID') : 'not yet established'));
            // check this Location for the PO of any open totes
            if(Session::has('locationID')) {
                $locationID = Session::get('locationID');
                $totes = $this->toteRepository->filterOn(['THOU.Location.parent' => $locationID],1);
                if(isset($totes) and count($totes) > 0) {
                    $tote = $totes[0];
                    Log::debug("locationID: $locationID, tote ".(isset($tote) ? $tote->Carton_ID : "not found"));
                    $poIDs = $this->purchaseOrderRepository->filterOn(['THOU.container.Tote' => $tote->Carton_ID],0);
                    if(isset($poIDs) and count($poIDs) > 0) {
                        $purchaseOrder = $this->purchaseOrderRepository->find($poIDs[0]->Purchase_Order);
                        $goodSoFar = True;
                        /*
                        if($itsFound == False) {
                            $goodSoFar = False;
                            $result['verdict'] = 'error';
                            $errors['Purchase_Order'][] = Lang::get('internal.errors.purchaseOrder.openTotes', ['Purchase_Order' => $poID->Purchase_Order, 'ToteID' => $tote->Carton_ID]);
                        }*/
                    }
                }
                //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'locationID', 'tote', 'poIDs', 'purchaseOrder', 'result', 'errors'));
            }
        }

        if($goodSoFar) {
            $this->setPurchaseOrder($quickReceive, $purchaseOrder);
            $result['verdict'] = 'valid';
        }
        if(!isset($result['verdict'])) {
            $result['verdict'] = 'not entered';
        }
        if($result['verdict'] == 'error') {
            Session::forget('poID');
            Session::forget('Rework');
            unset($quickReceive->Rework);
            unset($purchaseOrder);
        }
        Log::debug("result[verdict]: ".$result['verdict']);
        return $result;
    }

    protected function setPurchaseOrder(&$quickReceive, $purchaseOrder) {
        // set Session variables
        Session::put('poID', $purchaseOrder->objectID);
        $quickReceive->Purchase_Order = $purchaseOrder->Purchase_Order;
        Log::debug("Purchase_Order: $quickReceive->Purchase_Order");

        // record a text line
        $textLine = [
            'User_Name'   => Auth::user()->name,
            'Sender_Name' => Config::get('constants.application.name'),
            'Text'        => Lang::get('internal.articleFlow.selectPO', ['Purchase_Order' => $quickReceive->Purchase_Order]),
        ];
        if(Session::has('podID'))
            $textLine['POD'] = Session::get('podID');
        if(Session::has('articleID'))
            $textLine['Article'] = Session::get('articleID');
        $this->userConversationRepository->create($textLine);

        // if user pressed [Select a Purchase Order Number] button to get here, then we are finished with it
        if(Session::has('classID') && Session::get('classID') == 'Purchase_Order')
            Session::forget('classID');

        // calling into business logic
        ReceiveArticle::selectPO($purchaseOrder);
    }

    /**
     * @param $quickReceive
     * @return array $result['verdict'] = 'valid' | 'not entered' | 'error'
     * $result['Article'] = 'entered', $result['UPC'] = 'entered'
     */
    protected function validateArticle(&$quickReceive, &$errors) {
        $result = [];
        $goodSoFar = False;
        Log::debug(__METHOD__.'('.__LINE__.'):');

        // initiate the filter
        $filter = ['purchaseOrder.Status' => [Config::get('constants.purchaseOrderDetail.status.receiving'),Config::get('constants.purchaseOrderDetail.status.open')]];
        if(Session::has('poID')) {
            $filter['Order_Number'] = Session::get('poID');
        }

        // was Work Table entered?
        /* this can be ignored, they may be asking to change the Article at this location
        if(isset($quickReceive->Work_Table) && strlen($quickReceive->Work_Table) > 0) {
            if(isset($quickReceive->podLocation))
                $filter['Location'] = $quickReceive->podLocation;
        }
        */

        // was Article selected from a list?
        if(isset($quickReceive->podID) && strlen($quickReceive->podID) > 0) {
            $goodSoFar = True;
            $result['podID'] = 'selected';
            $filter['objectID'] = $quickReceive->podID;
        }

        // was Article entered?
        if(isset($quickReceive->Article) && strlen($quickReceive->Article) > 0) {
            $goodSoFar = True;
            $result['Article'] = 'entered';
            // Entered Article.UPC may imply a single Purchase_Order_Detail & Article
            $filter['item.Client_SKU'] = $quickReceive->Article;
        }

        // was UPC entered?
        if(isset($quickReceive->UPC) && strlen($quickReceive->UPC) > 0) {
            $goodSoFar = True;
            $result['UPC'] = 'entered';
            // Entered UPC may imply a single Purchase_Order_Detail & Article
            $filter['item.UPC'] = $quickReceive->UPC;
        }

        if($goodSoFar == False) {
            $result['verdict'] = 'not entered';
        }

        Log::debug("goodSoFar ".($goodSoFar ? "True" : "no").", count(filter): ".count($filter));
        // is entered value is a valid objectID or Client_SKU, can one pod be found?
        if(count($filter) > 1) {
            //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'errors'));
            $purchaseOrderDetails = $this->purchaseOrderDetailRepository->filterOn($filter,2);
            //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetails', 'goodSoFar', 'result'));
            Log::debug("count(purchaseOrderDetails): ".count($purchaseOrderDetails));
            if(count($purchaseOrderDetails) == 0) {
                $goodSoFar = False;
                $result['verdict'] = 'error';
                if(isset($quickReceive->Purchase_Order) && strlen($quickReceive->Purchase_Order) > 0 && isset($result['Article'])) {
                    //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetails', 'errors','result'));
                    $errors['Article'][] = Lang::get('internal.errors.article.notOfThisPO', [
                        'Article' => $quickReceive->Article, 'Purchase_Order' => $quickReceive->Purchase_Order
                    ]);
                } elseif(isset($quickReceive->Purchase_Order) && strlen($quickReceive->Purchase_Order) > 0 && isset($result['UPC'])) {
                    //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetails', 'errors','result'));
                    $errors['UPC'][] = Lang::get('internal.errors.upc.notOfThisPO', [
                        'UPC' => $quickReceive->UPC, 'Purchase_Order' => $quickReceive->Purchase_Order
                    ]);
                } elseif((!isset($quickReceive->Purchase_Order) || strlen($quickReceive->Purchase_Order) < 3) && isset($result['Article'])) {
                    $errors['Article'][] = Lang::get('internal.errors.article.notFound', [
                        'Article' => $quickReceive->Article
                    ]);
                } elseif((!isset($quickReceive->Purchase_Order) || strlen($quickReceive->Purchase_Order) < 3) && isset($result['UPC'])) {
                    $errors['UPC'][] = Lang::get('internal.errors.upc.notFound', [
                        'UPC' => $quickReceive->UPC
                    ]);
                }
            }
        }

        Log::debug("goodSoFar ".($goodSoFar ? "True" : "no").", count(filter): ".count($filter).
            (isset($purchaseOrderDetails) ? ", count(purchaseOrderDetails): ".count($purchaseOrderDetails) : ", purchaseOrderDetails not set"));
        if($goodSoFar and count($filter) > 1) {
            if(count($purchaseOrderDetails) == 1) {
                $purchaseOrderDetail = $purchaseOrderDetails[0];
                if(isset($result['podID'])) {
                    $article = $this->articleRepository->find($purchaseOrderDetail->SKU);
                    $quickReceive->Article = $article->Client_SKU;
                    $goodSoFar = True;
                    $result['Article'] = 'calculated';
                }
            } else {
                // let them select just one
                $goodSoFar = False;
                $result['verdict'] = 'not entered';
                //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetails', 'goodSoFar', 'result'));
            }
        }
        unset($purchaseOrderDetails);

        /*
         * Here we try to find the Article if it was not entered.
         */

        Log::debug("purchaseOrderDetail: ".(isset($purchaseOrderDetail) ? $purchaseOrderDetail->SKU : "not found"));
        if(isset($purchaseOrderDetail)) {
            //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetail', 'totes', 'errors'));
            $article = $this->articleRepository->find($purchaseOrderDetail->SKU);
            if(isset($article)) {
                //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetail', 'article', 'totes', 'errors'));
                if(!isset($quickReceive->Article) || strlen($quickReceive->Article) == 0) {
                    Log::debug("Article: null, found Article: $purchaseOrderDetail->SKU");

                    // Article was not entered, but we found it using Location ..
                    $goodSoFar = True;
                    $quickReceive->Article = $article->Client_SKU;
                }
            }
        }

        Log::debug("purchaseOrderDetail: ".(isset($purchaseOrderDetail) ? $purchaseOrderDetail->SKU : "not found"));
        if(!isset($purchaseOrderDetail)) {
            Log::debug("Session locationID: ".(Session::has('locationID') ? Session::get('locationID') : "not yet established"));
            // When the Article has not been set, check this Location for the Article of any open totes
            if(Session::has('locationID')) {
                $locationID = Session::get('locationID');
                $totes = $this->toteRepository->filterOn(['THOU.Location.parent' => $locationID],1);
                if(isset($totes) and count($totes) > 0) {
                    $tote = $totes[0];
                    Log::debug("tote: ".$tote->Carton_ID);
                    $podIDs = $this->purchaseOrderDetailRepository->filterOn(['THOU.container.Tote' => $tote->Carton_ID],0);
                    if(isset($podIDs) and count($podIDs) > 0) {

                        // Article was not entered, but we found it using open totes in this location
                        $goodSoFar = True;
                        $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($podIDs[0]->objectID);
                    }
                    //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'locationID', 'totes', 'podIDs', 'result', 'errors'));
                }
            }
        }

        Log::debug("goodSoFar ".($goodSoFar ? "True" : "no"));
        if($goodSoFar) {
            // is someone else tied to this Article?
            $userActivities = $this->userActivityRepository->getUserActivities($purchaseOrderDetail->objectID, Config::get('constants.userActivity.classID.ReceiveArticle'));
            if(isset($userActivities) && count($userActivities) > 0 && $userActivities[0]->User_Name != Auth::user()->name) {
                $goodSoFar = False;
                $result['verdict'] = 'error';
                if(isset($result['Article'])) {
                    $errors['Article'][] = Lang::get('internal.errors.article.alreadyInUse', [
                        'Article' => $quickReceive->Article, 'User_Name' => $userActivities[0]->User_Name
                    ]);
                    unset($quickReceive->Article);
                }
                if(isset($result['UPC'])) {
                    $errors['UPC'][] = Lang::get('internal.errors.upc.alreadyInUse', [
                        'UPC' => $quickReceive->UPC, 'User_Name' => $userActivities[0]->User_Name
                    ]);
                    unset($quickReceive->UPC);
                }
                Session::put('alreadyInUse', $userActivities[0]->User_Name);
            }
            //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetail', 'userActivities'));
        }

        /*
         * When they attempt to change the Article, they must close all the open totes first.
         */
        Log::debug("purchaseOrderDetail: ".(isset($purchaseOrderDetail) ? $purchaseOrderDetail->SKU : "not found"));
        if(isset($purchaseOrderDetail)) {
            Log::debug("Session locationID: ".(Session::has('locationID') ? Session::get('locationID') : "not yet established"));
            // Check this location for open totes of this Article.
            if(Session::has('locationID') and Session::has('podID') and Session::get('podID') != $purchaseOrderDetail->objectID) {
                $location = $this->locationRepository->find(Session::get('locationID'));
                $pod = $this->purchaseOrderDetailRepository->find(Session::get('podID'));
                $totes = $this->toteRepository->filterOn(['THOU.locID_and_podID' => [$location->objectID, $pod->objectID]],1);
                if(isset($totes) and count($totes) > 0) {
                    $tote = $totes[0];
                    $article = $this->articleRepository->find($pod->SKU);
                    Log::debug("tote: ".$tote->Carton_ID." contains: ".$article->Client_SKU);
                    $goodSoFar = False;
                    $result['verdict'] = 'error';
                    $errors['Article'][] = Lang::get('internal.errors.article.openTotes', ['Article' => $article->Client_SKU, 'ToteID' => $tote->Carton_ID, 'Location_Name' => $location->Location_Name]);
                    //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'locationID', 'totes', 'podIDs', 'result', 'errors'));

                    /*
                     * As it is, this prevents changing the Location if $purchaseOrderDetail->Location points to a Location with open totes
                     * the error is that it is prevented even when the totes are not for this Article.
                     *
                    if($goodSoFar and isset($purchaseOrderDetail->Location)) {
                        Log::debug("Article: $quickReceive->Article, & found Article: $article->Client_SKU");

                        // If there are no open totes we can switch to that Article.
                        // if there are open totes, error message "Open totes for this Article or UPC" ..
                        $totes = $this->toteRepository->filterOn(['THOU.locID_not_podID' => [$purchaseOrderDetail->Location, $purchaseOrderDetail->objectID]],2);
                        //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetail', 'totes', 'errors'));
                        if(isset($totes) && count($totes) > 0) {
                            $location = $this->locationRepository->find($purchaseOrderDetail->Location);
                            $goodSoFar = False;
                            $result['verdict'] = 'error';
                            $errors['Article'][] = Lang::get('internal.errors.article.alreadyOpen', ['Article' => $quickReceive->Article, 'Location_Name' => $location->Location_Name]);
                            $quickReceive->Article = $purchaseOrderDetail->SKU;
                        }
                        //dd(__METHOD__.'('.__LINE__.')', compact('quickReceive', 'filter', 'purchaseOrderDetail', 'article', 'totes', 'errors'));
                    }
                    */
                }
            }
        }

        if($goodSoFar) {
            $this->setArticle($quickReceive, $purchaseOrderDetail);
            $purchaseOrder = $this->purchaseOrderRepository->find($purchaseOrderDetail->Order_Number);
            $quickReceive->Purchase_Order = $purchaseOrder->Purchase_Order;
            $this->setPurchaseOrder($quickReceive, $purchaseOrder);
            $result['verdict'] = 'valid';
        }
        if(!isset($result['verdict'])) {
            $result['verdict'] = 'not entered';
        }
        if($result['verdict'] == 'error') {
            Session::forget('podID');
            Session::forget('articleID');
            Session::forget('Rework');
            unset($quickReceive->Rework);
        }
        unset($userActivities);
        Log::debug("result[verdict]: ".$result['verdict']);

        return $result;
    }

    protected function setArticle(&$quickReceive, $purchaseOrderDetail) {
        // set Session variables
        Session::put('podID', $purchaseOrderDetail->objectID);
        $article = $this->articleRepository->find($purchaseOrderDetail->SKU);
        if(isset($article)) {
            Session::put('articleID', $article->objectID);
            $quickReceive->Article = $article->Sku_Number;
            if(isset($article->rework)) {
                $quickReceive->Rework = $article->rework;
            } else {
                $quickReceive->Rework = '';
            }
            if(isset($article->split) && $article->split == 'N') {
                $quickReceive->Comingled = Lang::get('labels.Comingle');
            } else {
                $quickReceive->Comingled = '';
            }
            //dd(__METHOD__."(".__LINE__.")",compact('quickReceive','purchaseOrderDetail','article','quickReceive'));
            Log::debug("Article: $quickReceive->Article, Rework: $quickReceive->Rework, Comingled: $quickReceive->Comingled");

            // record a text line
            $textLine = [
                'POD'         => $purchaseOrderDetail->objectID,
                'Article'     => $article->objectID,
                'User_Name'   => Auth::user()->name,
                'Sender_Name' => Config::get('constants.application.name'),
                'Text'        => Lang::get('internal.articleFlow.selectArticle', ['Article' => $quickReceive->Article]),
            ];
            $this->userConversationRepository->create($textLine);

            // if user pressed [Select an Article Number] button to get here, then we are finished with it
            if(Session::has('classID') && Session::get('classID') == 'Article')
                Session::forget('classID');

            // calling into business logic
            ReceiveArticle::selectArticle($purchaseOrderDetail, $article);
        }
    }

    protected function setRework(&$quickReceive) {
        // update Article->rework
        $articleID = Session::get('articleID');
        $article = $this->articleRepository->find($articleID);
        if(isset($article->rework) and strlen($article->rework) > 2) {
            $quickReceive->Rework = $article->rework;
        } else {
            // our list box returns '0','none','low','medium' or 'high'
            $input_rework = Input::get('Rework');
            if( ctype_lower($input_rework{0}) ) {
                $rework = Lang::get('lists.article.rework.'.Input::get('Rework'));
                Log::debug("articleID: $articleID, update Rework: $rework");
                $this->articleRepository->update($articleID, ['rework' => $rework]);
                $quickReceive->Rework = $rework;

                $podID = Session::get('podID');
                // record a text line
                $textLine = [
                    'POD'         => $podID,
                    'Article'     => $articleID,
                    'User_Name'   => Auth::user()->name,
                    'Sender_Name' => Config::get('constants.application.name'),
                    'Text'        => Lang::get('internal.articleFlow.selectRework', ['Article' => $articleID, 'Rework' => $rework]),
                ];
                $this->userConversationRepository->create($textLine);
            }
        }

    }

    protected function putPalletIntoLocation($pallet, $location) {
        // add container; location as parent and pallet as object
        $this->locationRepository->putPalletIntoLocation($pallet->objectID, $location->objectID);

        // record a text line
        $textLine = [
            'User_Name'   => Auth::user()->name,
            'Sender_Name' => Config::get('constants.application.name'),
            'Text'        => Lang::get('internal.articleFlow.putPalletIntoLocation', ['palletID' => $pallet->Pallet_ID, 'locationID' => $location->Location_Name]),
        ];
        if(Session::has('podID'))
            $textLine['POD'] = Session::get('podID');
        if(Session::has('articleID'))
            $textLine['Article'] = Session::get('articleID');
        $this->userConversationRepository->create($textLine);
    }

    protected function getUserConversations() {
        // retrieve current UserConversation
        $filter = [
            'User_Name' => Auth::user()->name,
        ];
        if(Session::has('podID')) {
            $filter['POD'] = Session::get('podID');
        }
        if(Session::has('articleID')) {
            $filter['Article'] = Session::get('articleID');
        }
        if(count($filter) > 1) {
            $uc = $this->userConversationRepository->filterOn($filter, 15);
        } else {
            $uc = [];
        }
        return $this->padReverse($uc);
    }

    /**
     * pad and reverse an $are_eh
     */
    private function padReverse($are_eh) {
        $lines = [];
        $count = 0;
        $color = [];
        // capture the fields needed
        foreach($are_eh as $line) {
            $color[1] = $color[0] = 'black';
            $klass = '';
            if($line->Sender_Name == Config::get('constants.application.name')) {
                if(substr($line->Text,0,5) == 'Error') {
                    $color[1] = 'white; background:darkRed';
                } else {
                    $color[1] = 'darkblue';
                }
            } else {
                $klass = 'text-right';
            }
            $texts = array_reverse(explode(' - ', $line->Text));
            $i = 0;
            foreach($texts as $text) {
                $lines[] = (Object) ['Sender_Name' => $line->Sender_Name, 'Text' => $text, 'color' => $color[$i], 'klass' => $klass];
                $i = 1;
                if($count++ >= 14) {
                    break 2;
                }
            }
        }
        // pad out to 15 lines
        $lines2 = array_pad($lines, 15, (Object) ['Sender_Name' => '', 'Text' => '', 'color' => '', 'klass' => '']);
        // reverse the order
        return array_reverse($lines2);
    }

    /**
     * Merge a list Purchase Order Details and their Articles
     */
    public function mergePODetailWithArticle($filter) {
        $results = [];
        // get the PurchaseOrder
        $purchaseOrder = $this->purchaseOrderRepository->filterOn($filter,1);
        $filter['Order_Number'] = $purchaseOrder->objectID;
        // get the PurchaseOrderDetails
        $purchaseOrderDetails = $this->purchaseOrderDetailRepository->filterOn($filter, 0);
        //dd($purchaseOrderDetails);
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
                        'rework'                => isset($article->rework) ? $article->rework : '',
                    ];
                    // need a compound key here, for when multiple PODs point to the same Article
                    $key = $results[$article->Client_SKU.', '.$purchaseOrderDetails[$i]->objectID] = $result;
                }
            }

        }
        ksort($results);
        //dd(__METHOD__."(".__LINE__.")",compact('filter','purchaseOrder','purchaseOrderDetails','results'));
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
