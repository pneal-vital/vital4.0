<?php namespace vital3\Repositories;

use App\vital3\Inventory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use \Config;
use \Log;

class DBInventoryRepository implements InventoryRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
		if($limit == 0) {
            return $this->addTypes(Inventory::get());
        } elseif($limit == 1) {
            return $this->addTypes(Inventory::first());
        }
		return $this->addTypes(Inventory::limit($limit)->get());
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return $this->addTypes(Inventory::findOrFail($id));
	}

	protected function rawFilter($filter) {
		// Build a query based on filter $filter
		$query = Inventory::query()
            ->select('Inventory.*')
            ->orderBy('Created', 'desc');
		if(isset($filter['objectID']) && strlen($filter['objectID']) > 3) {
			$query->where('Inventory.objectID', 'like', $filter['objectID'] . '%');
		}
		if(isset($filter['Item']) && strlen($filter['Item']) > 3) {
			$query->where('Inventory.Item', 'like', $filter['Item'] . '%');
		}
		if(isset($filter['Created']) && strlen($filter['Created']) > 6) {
			$query->where('Inventory.Created', 'like', $filter['Created'] . '%');
		}
        if(isset($filter['Status']) && is_array($filter['Status']) > 0) {
            $query->whereRaw("Inventory.Status in ('" . implode("','", $filter['Status']) . "')");
        } elseif(isset($filter['Status']) && strlen($filter['Status']) > 1) {
            $query->where('Inventory.Status', 'like', $filter['Status'] . '%');
        }
        if(isset($filter['Order_Line']) && strlen($filter['Order_Line']) > 3) {
            $query->where('Inventory.Order_Line', 'like', $filter['Order_Line'] . '%');
        }
        if(isset($input['UOM']) && strlen($input['UOM']) > 3) {
            $query->where('Inventory.UOM', '=', $input['UOM']);
        }
        if(isset($filter['container.parent']) && strlen($filter['container.parent']) > 3) {
            $query->whereRaw('Inventory.objectID in (select objectID from container where parentID = '.$filter['container.parent'].')');
        }
        //TODO remove this if statement, check usage first
        if(isset($filter['Location.parent']) && strlen($filter['Location.parent']) > 3) {
            $query->whereRaw('objectID in (select inv.objectID from container plt  join container gc on gc.parentID = plt.objectID  join container inv on inv.parentID = gc.objectID  where plt.parentID = '.$filter['Location.parent'].')');
        }
        if(isset($filter['locationID']) && strlen($filter['locationID']) > 3) {
            $query->join('container as inv', 'inv.objectID', '=', 'Inventory.objectID')
                ->join('container as gc', 'gc.objectID', '=', 'inv.parentID')
                ->join('container as plt', 'plt.objectID', '=', 'gc.parentID')
                ->where('plt.parentID', $filter['locationID']);
        }
        //dd(__METHOD__."(".__LINE__.")",compact('filter','query'));
        return $query;
    }

    /**
     * Implement filterOn($filter)
     */
    public function filterOn($filter, $limit=10) {

        if(isset($filter['THOU.container.parent'])) {
            return DB::connection('vitaldev')
                ->table('Inventory')
                ->join('container as inv', 'inv.objectID', '=', 'Inventory.objectID')
                ->join('Generic_Container', 'Generic_Container.objectID', '=', 'inv.parentID')
                ->select('Inventory.*')
                ->where('Generic_Container.objectID', $filter['THOU.container.parent'])
                ->orWhere('Generic_Container.Carton_ID', $filter['THOU.container.parent'])
                ->get();
        }
        if(isset($filter['THOU.articleID'])) {
            return DB::connection('vitaldev')
                ->table('Inventory')
                ->join('itemKit','itemKit.objectID', '=', 'Inventory.Item')
                ->select('Inventory.*')
                ->where('itemKit.parentID', $filter['THOU.articleID'])
                ->where('Inventory.Item', '>', '0')
                ->get();
        }

        if($limit == 0) {
            return $this->addTypes($this->rawFilter($filter)->get());
        } elseif($limit == 1) {
            Log::debug(__METHOD__."(".__LINE__."):  filter: ".(is_array($filter) ? 'is_array' : 'not array'));
            Log::debug($filter);
            // ->first() decided not to work, when result should be null, it returns $this;
            // so we can get the first this way.
            $inventories = $this->rawFilter($filter)->limit(1)->get();
            Log::debug(__METHOD__."(".__LINE__."):  rawFilter(..)->limit(1)->get(): ".get_class($inventories));
            Log::debug($inventories);
            if(!$inventories->isEmpty()) {
                $inventory = $this->addTypes($inventories->first());
                Log::debug(__METHOD__."(".__LINE__."):  Eloquent\\Collection->first(): ".get_class($inventory));
                Log::debug($inventory);
                return $inventory;
            } else {
                return null;
            }
            //$inv = $this->rawFilter($filter)->limit(1)->get();
            //Log::debug(__METHOD__."(".__LINE__."):  retrieved: ".get_class($inv));
            //Log::debug($inv);
            //return $this->addTypes($this->rawFilter($filter)->first());
        }
        Log::debug(__METHOD__."(".__LINE__."):  filterOn: ",$filter);
        $results = $this->rawFilter($filter)->limit($limit)->get();
        Log::debug(__METHOD__."(".__LINE__."):  results: ".(isset($results) ? count($results) : "null"));

        return $this->addTypes($results);
    }

    /**
     * Implement filterOn($filter)
     */
    public function paginate($filter) {
		return $this->addTypes($this->rawFilter($filter)->paginate(10));
	}

    private function addTypes($invContainer) {
        /*
         * First we access a Collection[Inventory] from whatever container we received
         */
        //dd(__METHOD__."(".__LINE__.")",compact('inventories'));
        // if $invs is a paginator
        $inventories = $invContainer;
        if(is_a($invContainer, 'Illuminate\Pagination\LengthAwarePaginator')) {
            $inventories = $invContainer->items();
            //dd(__METHOD__."(".__LINE__.")",compact('inventories','convertBack'));
        }
        // if $inventories is not, make it an array
        elseif(!is_array($invContainer) && !is_a($invContainer, 'Illuminate\Support\Collection')) {
            $inventories = array($invContainer);
        }
        //dd(__METHOD__."(".__LINE__.")",compact('invContainer','inventories'));
        Log::debug(__METHOD__."(".__LINE__."):  invContainer class: ".get_class($invContainer));
        Log::debug(__METHOD__."(".__LINE__."):  inventories class: ".(is_array($inventories) ? "array(".count($inventories).")" : get_class($inventories)));

        /*
         * update the Inventory record(s) within our Collection[Inventory]
         */
        // get the Item objectIDs and Order_Line objectIDs referenced by this Inventory
        $itemIDs = array();
        $detailIDs = array();
        foreach($inventories as $inventory) {
            //Log::debug(__METHOD__."(".__LINE__."):  ".(is_array($inventories) ? "array(".count($inventories).")" : get_class($inventories))." as ".get_class($inventory));
            //Log::debug($inventory);
            $itemIDs[$inventory->Item] = $inventory;
            $detailIDs[$inventory->Order_Line] = $inventory;
        }
        //dd(compact('inventories', 'itemIDs', 'detailIDs'));

        // determine the type of Item each refers to
        if(count($itemIDs) > 0) {
            // Are they UPCs?
            $upcs = DB::connection('vitaldev')
                ->table('Item')
                ->join('itemKit', 'itemKit.objectID', '=', 'Item.objectID')
                ->select('itemKit.objectID', 'Item.Client_SKU')
                ->whereIn('itemKit.objectID', array_keys($itemIDs))
                ->get();
            foreach ($upcs as $upc) {
                foreach ($inventories as $inventory) {
                    if ($inventory->Item == $upc->objectID) {
                        $inventory->Item_type = Config::get('constants.itemKit.objectID.pointsTo');
                        $inventory->Item_typeID = $upc->Client_SKU;
                    }
                }
            }

            // Are they Articles?
            $articles = DB::connection('vitaldev')
                ->table('Item')
                ->join('itemKit', 'itemKit.parentID', '=', 'Item.objectID')
                ->select('itemKit.parentID', 'Item.Client_SKU')
                ->whereIn('itemKit.parentID', array_keys($itemIDs))
                ->get();
            foreach ($articles as $article) {
                foreach ($inventories as $inventory) {
                    if ($inventory->Item == $article->objectID) {
                        $inventory->Item_type = Config::get('constants.itemKit.parentID.pointsTo');
                        $inventory->Item_typeID = $article->Client_SKU;
                    }
                }
            }
            //dd(compact('inventories', 'upcs', 'articles'));
        }

        // determine the type of Order_Detail each refers to
        if(count($detailIDs) > 0) {
            // Are they Purchase Order Details?
            $purchaseOrderDetails = DB::connection('vitaldev')
                ->table('Inbound_Order_Detail')
                ->join('Inbound_Order', 'Inbound_Order.objectID', '=', 'Inbound_Order_Detail.Order_Number')
                ->select('Inbound_Order_Detail.objectID', 'Inbound_Order.Purchase_Order')
                ->whereIn('Inbound_Order_Detail.objectID', array_keys($detailIDs))
                ->get();
            foreach ($purchaseOrderDetails as $purchaseOrderDetail) {
                foreach ($inventories as $inventory) {
                    if ($inventory->Order_Line == $purchaseOrderDetail->objectID) {
                        $inventory->Order_Line_type = Config::get('constants.inventory.orderLine.pointsTo.inbound');
                        $inventory->Order_Line_typeID = $purchaseOrderDetail->Purchase_Order;
                    }
                }
            }

            // Are they Outbound Order Details?
            $outboundOrderDetails = DB::connection('vitaldev')
                ->table('Outbound_Order_Detail')
                ->join('Outbound_Order', 'Outbound_Order.objectID', '=', 'Outbound_Order_Detail.Order_Number')
                ->select('Outbound_Order_Detail.objectID', 'Outbound_Order.Order_Number')
                ->whereIn('Outbound_Order_Detail.objectID', array_keys($detailIDs))
                ->get();
            foreach ($outboundOrderDetails as $outboundOrderDetail) {
                foreach ($inventories as $inventory) {
                    if ($inventory->Order_Line == $outboundOrderDetail->objectID) {
                        $inventory->Order_Line_type = Config::get('constants.inventory.orderLine.pointsTo.outbound');
                        $inventory->Order_Line_typeID = $outboundOrderDetail->Order_Number;
                    }
                }
            }
            //dd(compact('inventories', 'purchaseOrderDetails', 'outboundOrderDetails'));
        }

        /*
         * then return the original container.
         */
        return $invContainer;
    }

    /**
     * Implement quantityOn($filter)
     */
    public function quantityOn($filter) {
        $quantity = 0;
        //dd($filter);
        $inventories = $this->rawFilter($filter)->get();
        foreach($inventories as $inventory) {
            $quantity += $inventory->Quantity;
        }
        return $quantity;
    }

	/**
	 * Implement getFromDate()
	 */
	public function getFromDate($from) {
		// using the Eloquent model
		//return Inventory::latest('Created')->where('Created', '>=', Carbon::now())->get();
		return Inventory::latest('Created')->where('Created', '>=', Carbon::parse($from))->get();
	}

	/**
	 * Implement create($input)
	 */
	public function create($input) {
		return Inventory::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $inventory = Inventory::find($id);
        Log::debug(__METHOD__."(".__LINE__."):  invID: $id, input: ");
        Log::debug($input);

        return $inventory->update($input);
    }

    public function onHandReport($UPCs) {
        $onHandInventories = [];
        if(isset($UPCs) and count($UPCs)) {
            /*
select case Item_Additional.Value when 'Y' then 'Split' else 'Comingled' end as split
     , case Item_Additional.Value when 'Y' then UPC.Description else Article.Description end as Description
     , sum(UPCinven.Quantity) as UPCinvQty, UPCinven.Status as UPCinvSt
     , UPCgen_con.Carton_ID
     , UPClctn.Location_Name
  from Generic_Container
  join container inv      on inv.parentID       = Generic_Container.objectID
  join Inventory          on Inventory.objectID = inv.objectID
  join Item               on Item.objectID      = Inventory.Item
  join itemKit            on itemKit.objectID   = Item.objectID
  join Item_Additional    on Item_Additional.objectID = itemKit.parentID and Item_Additional.Name = 'split'
  join Item Article       on Article.objectID   = itemKit.parentID
  join itemKit ik2        on ik2.parentID       = itemKit.parentID
  join Item UPC           on UPC.objectID       = itemKit.objectID
  join Inventory UPCinven on UPCinven.Item      = UPC.objectID and UPCinven.Status in ('OPEN','REPLEN','A-REPLEN','AG-REPLEN','REC','RECD')
  join container UPCinv   on UPCinv.objectID    = UPCinven.objectID
  join Generic_Container UPCgen_con on UPCgen_con.objectID = UPCinv.parentID
  join container UPCgc    on UPCgc.objectID     = UPCinv.parentID
  join Pallet UPCpllt     on UPCpllt.objectID   = UPCgc.parentID
  join container UPCplt   on UPCplt.objectID    = UPCgc.parentID
  join Location UPClctn   on UPClctn.objectID   = UPCplt.parentID
 where ( Generic_Container.objectID = '6232065850' or Generic_Container.Carton_ID = '6232065850' )
 group by UPClctn.Location_Name, UPCinven.Status
 order by sum(UPCinven.Quantity) desc, UPClctn.Location_Name, UPCinven.Status;
             */
            Log::debug(__METHOD__."(".__LINE__."):  count(UPCs): ".count($UPCs));
/*
        // display current on hand quantities
        $onHandInventories = DB::connection("vitaldev")
            ->table("Generic_Container")
            ->select("case Item_Additional.Value when 'Y' then 'Split' else 'Comingled' end as split"
                   , "case Item_Additional.Value when 'Y' then UPC.Description else Article.Description end as Description"
                   , "sum(UPCinven.Quantity) as UPCinvQty"
                   , "UPCinven.Status as UPCinvSt"
                   , "UPCgen_con.Carton_ID"
                   , "UPClctn.Location_Name")
            ->join("container as inv",      "inv.parentID",       "=", "Generic_Container.objectID")
            ->join("Inventory",             "Inventory.objectID", "=", "inv.objectID")
            ->join("Item",                  "Item.objectID",      "=", "Inventory.Item")
            ->join("itemKit",               "itemKit.objectID",   "=", "Item.objectID")
            ->join("Item_Additional",       "Item_Additional.objectID", "=", "itemKit.parentID and Item_Additional.Name = 'split'")
            ->join("Item as Article",       "Article.objectID",   "=", "itemKit.parentID")
            ->join("itemKit as ik2",        "ik2.objectID",       "=", "itemKit.parentID")
            ->join("Item as UPC",           "UPC.objectID",       "=", "itemKit.objectID")
            ->join("Inventory as UPCinven", "UPCinven.Item",      "=", "UPC.objectID and UPCinven.Status in ('OPEN','REPLEN','A-REPLEN','AG-REPLEN','REC','RECD')")
            ->join("container as UPCinv",   "UPCinv.objectID",    "=", "UPCinven.objectID")
            ->join("Generic_Container as UPCgen_con", "UPCgen_con.objectID", "=", "UPCinv.parentID")
            ->join("container as UPCgc",    "UPCgc.objectID",     "=", "UPCinv.parentID")
            ->join("Pallet as UPCpllt",     "UPCpllt.objectID",   "=", "UPCgc.parentID")
            ->join("container as UPCplt",   "UPCplt.objectID",    "=", "UPCgc.parentID")
            ->join("Location as UPClctn",   "UPClctn.objectID",   "=", "UPCplt.parentID")
            ->where("Generic_Container.objectID", "=", $id)
            ->orWhere("Generic_Container.Carton_ID", "=", $id)
            ->groupBy("UPClctn.Location_Name", "UPCinven.Status")
            ->orderBy("sum(UPCinven.Quantity)", "desc")
            ->orderBy("UPClctn.Location_Name")
            ->orderBy("UPCinven.Status")
            ->get();
*/
            /*
             * So at this point we have a list of UPCs.
             * - Each UPC can be associated with multiple Articles
             * - Each parent Article may have comingle/split set (or not set, defaults to split)
             * - we want to display inventory in it's various locations for each UPC
             *   or sum up to the Article level???
             *
             * Just stay at the UPC level
             * - comingled or not, if they don't have any 'Large' in a forward pick face location they want to know.
             */
            $upcIDs = implode(',',array_keys($UPCs));
            $rawSelect = "
select objectID, Client_SKU, Description, sum(Quantity) as Quantity, Status, Carton_ID, Pallet_ID, Location_Name, LocType from (
select STRAIGHT_JOIN UPC.objectID, UPC.Client_SKU, UPC.Description
     , Inventory.Quantity, Inventory.Status
     , Generic_Container.Carton_ID
     , Pallet.Pallet_ID
     , Location.Location_Name
     , ifnull(case substring(Location.LocType,1,4) when 'ACTI' then 'Activity' when 'RESE' then 'Reserve' else substring(Location.LocType,1,4) end
            , case substring(Pallet.Pallet_ID,1,3) when 'RES' then 'PA2 Reserve' when 'FWP' then 'PA2 Pick' else 'Limbo' end) as LocType
  from Inventory
  join Item UPC on UPC.objectID = Inventory.Item
  join container inv on inv.objectID = Inventory.objectID
  join Generic_Container on Generic_Container.objectID = inv.parentID
  join container gc on gc.objectID = inv.parentID
  join Pallet on Pallet.objectID = gc.parentID
  left join container plt on plt.objectID = gc.parentID
  left join Location on Location.objectID = plt.parentID
 where UPC.objectID in ( ".$upcIDs." )
   and Inventory.Status in ('OPEN','REPLEN','A-REPLEN','AG-REPLEN','REC','RECD','PUTAWAY')
) a
 group by Client_SKU, Status, LocType
 order by Client_SKU, Status, LocType;
           ";
            Log::debug(__METHOD__."(".__LINE__."):  upcIDs: ".$upcIDs);
            $onHandInventories = DB::connection("vitaldev")->select($rawSelect, []);
        }

        Log::debug(__METHOD__."(".__LINE__."):  ".count($onHandInventories));
        //dd(__METHOD__."(".__LINE__.")",compact('UPCs', 'upcIDs', 'params', 'onHandInventories'));

        return $onHandInventories;
    }

    /**
     * Count of active UPC definitions
     */
    public function countUPCs() {
        $results = DB::connection('vitaldev')->select("
select count(distinct UPC.objectID) as UPCs from Item UPC
  join itemKit on itemKit.objectID = UPC.objectID
  join Inventory on Inventory.Item = UPC.objectID and Inventory.Status != 'SHIPPED' and Inventory.Quantity > 0
            ");
        return (count($results) ? $results[0]->UPCs : 0);
    }

}
