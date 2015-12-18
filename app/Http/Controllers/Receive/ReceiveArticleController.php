<?php namespace App\Http\Controllers\Receive;

use App\Http\Controllers\Controller;
use vital3\Repositories\InventoryRepositoryInterface;
use vital3\Repositories\LocationRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\PurchaseOrderDetailRepositoryInterface;
use vital40\Repositories\PurchaseOrderRepositoryInterface;
use vital40\Repositories\ReceiptHistoryRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use vital40\Repositories\UserConversationRepositoryInterface;
use \Auth;
use \Config;
use \Entrust;
use \Input;
use \Lang;
use \Log;
use \ReceiveArticle;
use \Request;
use \Response;
use \Session;

/**
 * Class ReceiveArticleController
 * @package App\Http\Controllers
 */
class ReceiveArticleController extends Controller {

    /**
     * Reference an implementation of the Repository Interface
     * @var vital40\Repositories\PurchaseOrderDetailRepositoryInterface
     */
    protected $articleRepository;
    protected $inventoryRepository;
    protected $locationRepository;
    protected $purchaseOrderDetailRepository;
    protected $purchaseOrderRepository;
    protected $receiptHistoryRepository;
    protected $upcRepository;
    protected $userActivityRepository;
    protected $userConversationRepository;
    protected $receivePOController;

    /**
	 * Constructor requires ReceiveArticle Repository
	 */ 
	public function __construct(
          ArticleRepositoryInterface $articleRepository
        , InventoryRepositoryInterface $inventoryRepository
        , LocationRepositoryInterface $locationRepository
        , PurchaseOrderDetailRepositoryInterface $purchaseOrderDetailRepository
        , PurchaseOrderRepositoryInterface $purchaseOrderRepository
        , ReceiptHistoryRepositoryInterface $receiptHistoryRepository
        , UPCRepositoryInterface $upcRepository
        , UserActivityRepositoryInterface $userActivityRepository
        , UserConversationRepositoryInterface $userConversationRepository
        , ReceivePOControllerInterface $receivePOController
    ) {
        $this->articleRepository = $articleRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->locationRepository = $locationRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->receiptHistoryRepository = $receiptHistoryRepository;
        $this->upcRepository = $upcRepository;
        $this->userActivityRepository = $userActivityRepository;
        $this->userConversationRepository = $userConversationRepository;
        $this->receivePOController = $receivePOController;
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
        if(isset($purchaseOrder) == false) {
            return redirect()->route('receivePO.index');
        }

        // Do we know Article?
        $filter = [
            'classID'   => Config::get('constants.userActivity.classID.ReceiveArticle'),
            'User_Name' => Auth::user()->name,
        ];
        $userActivity = $this->userActivityRepository->filterOn($filter, $limit = 1);
        if(isset($userActivity)) {
            $purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($userActivity->id);
        }
        if(isset($purchaseOrderDetail)) {
            return $this->show($purchaseOrderDetail->objectID);
        } else {
            return $this->receivePOController->show($purchaseOrder->objectID);
        }
    }

    /**
     * Display a Filtered Listing of the resource.
     */
    public function filter() {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        //check if its our form
        if( Session::token() !== Input::get( '_token' ) ) {
            Log::error(__METHOD__.'('.__LINE__.'):   token: '.Session::token());
            Log::error(__METHOD__.'('.__LINE__.'):  _token: '.Input::get( '_token' ));
            if(Request::ajax())
                return Response::json(['msg' => 'Unauthorized attempt to refresh texting area']);
            else
                return redirect()->route('home');
        }

        Log::debug(__METHOD__.'('.__LINE__.'):  '.(Request::ajax() ? 'Ajax Input' : 'Http Input'), Input::all());

        //$requestAll = (object) Request::all();
        //dd(__METHOD__.'('.__LINE__.'):',compact('requestAll'));

        // Button Leave_Article_Receiving
        if(Request::has('btn_leave')) {
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

        // Button Review_History
        if(Request::has('btn_history')) {
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

        $requestAll = (object) Request::all();
        dd(__METHOD__.'('.__LINE__.'):',compact('requestAll'));
    }

    /**
	 * Display a specific resource
	 * In this case, PurchaseOrderDetails and Article for this PurchaseOrderDetail $id
	 */
	public function show($id) {
        if(Entrust::can('receive') == false) return redirect()->route('home');

        Log::debug(__METHOD__.'('.__LINE__.'):  id: '.$id);

		// using an implementation of the ReceiveArticle Repository Interface
		$purchaseOrderDetail = $this->purchaseOrderDetailRepository->find($id);

		// using an implementation of the ReceiveArticle Repository Interface
		$article = $this->articleRepository->find($purchaseOrderDetail->SKU);
		//dd($article);

        Session::put('podID', $purchaseOrderDetail->objectID);
        Session::put('articleID', $article->objectID);

		// calling into business logic
		ReceiveArticle::selectArticle($purchaseOrderDetail, $article);

        /*
         * if user has already selected a location, and now is associating a purchaseOrderDetail line
         * then we should associate the purchaseOrderDetail line with the location
         */
        if(Session::has('locationID')) {
            ReceiveArticle::setPurchaseOrderDetailLocation($purchaseOrderDetail->objectID, Session::get('locationID'));
        }

        // calling into business logic, calculate the UPCs grid
        $upcs = ReceiveArticle::receivedUPCsGrid($purchaseOrderDetail->objectID, $article->objectID);

        // retrieve ReceiptHistory, & current UserConversation
        $filter =[
            'POD' => $purchaseOrderDetail->objectID,
            'Article' => $article->objectID,
            'User_Name' => Auth::user()->name,
        ];
        $receiptHistories = $this->receiptHistoryRepository->filterOn($filter, 3)->reverse();
        $userConversations = $this->padReverse($this->userConversationRepository->filterOn($filter,15));

		return view('pages.receiveArticle.show', compact('purchaseOrderDetail', 'article', 'upcs', 'receiptHistories', 'userConversations'));
	}

    /**
     * pad and reverse an $are_eh
     */
    private function padReverse($are_eh) {
        $lines = [];
        $count = 0;
        // capture the fields needed
        foreach($are_eh as $line) {
            $color = 'black';
            $klass = '';
            if($line->Sender_Name == Config::get('constants.application.name')) {
                $color = 'darkblue';
            } else {
                $klass = 'text-right';
            }
            $texts = array_reverse(explode(' - ', $line->Text));
            foreach($texts as $text) {
                $lines[] = (Object) ['Sender_Name' => $line->Sender_Name, 'Text' => $text, 'color' => $color, 'klass' => $klass];
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
     * ajax refresh the UPCs grid and Last Tasks Completes lines
     */
    public function refresh() {
        // TODO - Add ENTRUST authorization check

        //check if its our form
        if( Session::token() !== Input::get( '_token' ) ) {
            return Response::json( array(
                'msg' => 'Unauthorized attempt to refresh the UPC grid'
            ) );
        }

        Log::debug(__METHOD__.'('.__LINE__.'):  '.(Request::ajax() ? 'Ajax Input' : 'Http Input'), Input::all());

        //$setting_name = Input::get( 'setting_name' );
        //$setting_value = Input::get( 'setting_value' );
        $podID = Session::get('podID');
        $articleID = Session::get('articleID');

        // calling into business logic, recalculate the UPCs grid
        $upcs = ReceiveArticle::receivedUPCsGrid($podID, $articleID);

        // retrieve ReceiptHistory
        $filter =[
            'POD' => $podID,
            'Article' => $articleID,
            'User_Name' => Auth::user()->name,
        ];
        $receiptHistories = $this->receiptHistoryRepository->filterOn($filter, 3)->reverse();

        $jsonResponse = Response::json( compact('upcs', 'receiptHistories') );
        Log::debug(__METHOD__.'('.__LINE__.'):  jsonResponse');
        Log::debug(var_dump($jsonResponse));

        return $jsonResponse;
    }

    /**
     * ajax refresh the texting area
     */
    public function texting() {
        // TODO - Add ENTRUST authorization check

        //check if its our form
        if( Session::token() !== Input::get( '_token' ) ) {
            return Response::json( array(
                'msg' => 'Unauthorized attempt to refresh texting area'
            ) );
        }

        Log::debug(__METHOD__.'('.__LINE__.'):  '.(Request::ajax() ? 'Ajax Input' : 'Http Input'), Input::all());

        $podID = Session::get('podID');
        $articleID = Session::get('articleID');
        $text_entry = Input::get( 'text_entry' );
        $clicked = Input::get('clicked');
        Log::debug(__METHOD__."(".__LINE__."):  podID: $podID, articleID: $articleID");
        Log::debug($text_entry);
        Log::debug($clicked);

        // record the new text_entry
        $newText = [
            'POD' => $podID,
            'Article' => $articleID,
            'User_Name' => Auth::user()->name,
            'Sender_Name' => Auth::user()->name,
            'Text' => $text_entry,
            'clicked' => $clicked,
        ];
        $this->userConversationRepository->create($newText);

        // calling into business logic
        $responseText = ReceiveArticle::textEntry($newText);

        $this->userConversationRepository->create($responseText);

        // current UserConversation
        $filter = [
            'POD' => $podID,
            'Article' => $articleID,
            'User_Name' => Auth::user()->name,
        ];
        $userConversations = $this->padReverse($this->userConversationRepository->filterOn($filter,15));

        $jsonResponse = Response::json( compact('userConversations', 'responseText') );
        Log::debug(__METHOD__.'('.__LINE__.'):  jsonResponse');
        Log::debug(var_dump($jsonResponse));

        return $jsonResponse;
    }

}
