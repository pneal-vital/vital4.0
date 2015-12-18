<?php namespace vital40\Repositories;

use App\vital3\InboundOrder;
use App\vital3\InboundOrderAdditional;
use Illuminate\Support\Facades\DB;

class DBPurchaseOrderRepository implements PurchaseOrderRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
        if($limit == 0) {
            $purchaseOrders =  InboundOrder::orderBy('Created', 'desc')->get();
        } elseif($limit == 1) {
            return $this->findAdditional(InboundOrder::orderBy('Created', 'desc')->first());
        } else {
            // using the Eloquent model
            $purchaseOrders = InboundOrder::orderBy('Created', 'desc')->limit($limit)->get();
        }

        foreach($purchaseOrders as $purchaseOrder) {
            $this->findAdditional($purchaseOrder);
        }

        return $purchaseOrders;
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
        // did they give us objectID or Purchase_Order?
        $purchaseOrder = InboundOrder::where('Purchase_Order', $id)->first();
        if(!isset($purchaseOrder))
            $purchaseOrder = InboundOrder::find($id);

        return $this->findAdditional($purchaseOrder);
	}

	/**
	 * Implement findID($id)
	 */
	public function findID($id) {
		// using the Eloquent model
        $purchaseOrder = InboundOrder::findOrFail($id);

        return $this->findAdditional($purchaseOrder);
	}

    /**
     * @param $purchaseOrder
     */
    protected function findAdditional(&$purchaseOrder)
    {
        /*
         * Add these fields from InboundOrder_Additional
            | RouteNumber         | varchar(85)  | YES  |     | NULL    |       |
         */
        if(isset($purchaseOrder)) {
            $additionals = InboundOrderAdditional::whereObjectid($purchaseOrder->objectID)->limit(20)->get();
            foreach ($additionals as $additional) {
                $purchaseOrder->$additional['Name'] = $additional['Value'];
            }
            //dd($purchaseOrder);
        }

        return $purchaseOrder;
    }

	protected function rawFilter($filter) {
        //dd(__METHOD__."(".__LINE__.")",compact('filter'));
		// Build a query based on filter $input
		$query = InboundOrder::orderBy('Created', 'desc');
        foreach($filter as $key => $value) {
            if ($key == 'objectID' && strlen($value) > 3) {
                $query = $query->where($key, 'like', $value . '%');
            }
            if ($key == 'Purchase_Order' && strlen(trim($value)) > 3) {
                $query = $query->where($key, 'like', trim($value) . '%');
            } elseif ($key == 'Order_Number' && strlen($value) > 3) {
                $query = $query->where($key, 'like', $value . '%');
            } elseif ($key == 'Client' && strlen($value) > 3) {
                $query = $query->where($key, 'like', $value . '%');
            } elseif ($key == 'Invoice_Number' && strlen($value) > 3) {
                $query = $query->where($key, 'like', $value . '%');
            } elseif ($key == 'Status' && is_array($value)) {
                $query = $query->whereRaw($key . " in ('" . implode("','", $value) . "')");
            } elseif ($key == 'Status' && strlen($value) > 2) {
                $query = $query->where($key, 'like', $value . '%');
            } elseif ($key == 'Created' && strlen($value) > 4) {
                preg_match('/last ([\d]+) days/', $value, $matches);
                if (count($matches) > 0) {
                    $query = $query->whereRaw($key . ' > DATE_SUB(NOW(), INTERVAL ' . $matches[1] . ' DAY)');
                    //dd('Created > DATE_SUB(NOW(), INTERVAL '.$matches[1].' DAY)');
                } else {
                    $query = $query->where($key, 'like', $value . '%');
                    //dd(__METHOD__."(".__LINE__.")",compact('filter','key','value','query'));
                }
            } elseif ($key == 'Expected' && strlen($value) > 4) {
                $query = $query->where($key, 'like', preg_replace('/[-:]/', '', $value) . '%');
                //dd(__METHOD__."(".__LINE__.")",compact('filter','key','value','query'));
            } else {
                //TODO Replace this with Eloquent Relationships, one to one|many
                preg_match('/item\.(.*)/', $key, $matches);
                if (count($matches) > 0) {
                    /* $query = $query->whereRaw("objectID in (select distinct Inbound_Order_Detail.Order_Number from Inbound_Order_Detail join Item on Item.objectID = Inbound_Order_Detail.SKU where Item.$matches[1] = '$value')");
                     *
                     * The above line work great when you wanting to search the Article.{fieldName} columns.
                     * Here we would like to extend that to search the UPCs as well.
                     *
                     * Relationship is, a PurchaseOrderDetail.SKU field points to an Article,
                     * Article(Item entry) -> (parentID on) itemKit (has objectID) -> UPC(Item entries)
                     *
                     * User may have provided Article.UPC or UPC.UPC, so we check them both ..
                     */
                    // when string containing digits, left trim zeros
                    $upcValue = ctype_digit($value) ? ltrim($value, '0') : $value;
                    $subQueryWhereClause = " where Article.$matches[1] = '$upcValue' or UPC.$matches[1] = '$upcValue'";
                    //dd(compact('key','matches','value','subQueryWhereClause'));
                    $query = $query->whereRaw("objectID in (select distinct Inbound_Order_Detail.Order_Number  from Inbound_Order_Detail  join Item as Article on Article.objectID = Inbound_Order_Detail.SKU  left join itemKit on itemKit.parentID = Article.objectID  left join Item as UPC on UPC.objectID = itemKit.objectID $subQueryWhereClause )");
                }
            }
        }
        return $query;
    }

    /**
     * Implement filterOn($filter, $limit)
     */
    public function filterOn($filter, $limit=10) {
        // Queries that had performance issues.
        if(isset($filter['THOU.container.Tote'])) {
            //dd(compact('input','limit'));
            return DB::connection('vitaldev')
                ->table('Generic_Container')
                ->join('container', 'container.parentID', '=', 'Generic_Container.objectID')
                ->join('Inventory', 'Inventory.objectID', '=', 'container.objectID')
                ->join('Inbound_Order_Detail', 'Inbound_Order_Detail.objectID', '=', 'Inventory.Order_Line')
                ->join('Inbound_Order', 'Inbound_Order.objectID', '=', 'Inbound_Order_Detail.Order_Number')
                ->select('Inbound_Order.Purchase_Order')
                ->where('Generic_Container.Carton_ID', $filter['THOU.container.Tote'])
                ->distinct()->get();
        }

        if($limit == 0) {
            $purchaseOrders = $this->rawFilter($filter)->get();
        } else if($limit == 1) {
            $purchaseOrder = $this->rawFilter($filter)->first();
            return $this->findAdditional($purchaseOrder);
        } else {
            $purchaseOrders = $this->rawFilter($filter)->limit($limit)->get();
        }

        foreach($purchaseOrders as $purchaseOrder) {
            $this->findAdditional($purchaseOrder);
        }

        return $purchaseOrders;
	}

    /**
     * Implement paginate($filter)
     */
    public function paginate($filter) {
        $purchaseOrders = $this->rawFilter($filter)->paginate(10);

        foreach($purchaseOrders as $purchaseOrder) {
            $this->findAdditional($purchaseOrder);
        }

        return $purchaseOrders;
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $po = InboundOrder::find($id);

        $updatedPo = $po->update($input);

        //TODO find a way to automate which $name's are Additional, should not be hardcoded here.
        //TODO Found it!, if we subtract original attribute names from attributes, we get this list of Additional names
        $name = 'RouteNumber';

        if(isset($input[$name])) {
            $additional = InboundOrderAdditional::where('objectID', $id)->where('Name', $name)->first();
            if(isset($additional)) {
                $additional->update(['Value' => $input[$name]]);
            } else {
                InboundOrderAdditional::create(['objectID' => $id, 'Name' => $name, 'Value' => $input[$name]]);
            }
        }

        return $updatedPo;
    }

}
