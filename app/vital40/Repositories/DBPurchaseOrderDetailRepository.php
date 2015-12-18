<?php namespace vital40\Repositories;

use App\vital3\InboundOrderDetail;
use App\vital3\InboundOrderDetailAdditional;
use Illuminate\Support\Facades\DB;
use \Session;

class DBPurchaseOrderDetailRepository implements PurchaseOrderDetailRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
        if($limit == 1) {
            return $this->findAdditional(InboundOrderDetail::orderBy('Order_Number', 'desc')->first());
        } else if($limit == 0) {
            $purchaseOrderDetails = InboundOrderDetail::orderBy('Order_Number', 'desc')->get();
        } else {
            // using the Eloquent model
            $purchaseOrderDetails = InboundOrderDetail::orderBy('Order_Number', 'desc')->limit($limit)->get();
        }

        foreach($purchaseOrderDetails as $purchaseOrderDetail) {
            $this->findAdditional($purchaseOrderDetail);
        }

        return $purchaseOrderDetails;
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		$purchaseOrderDetail = InboundOrderDetail::findOrFail($id);

        return $this->findAdditional($purchaseOrderDetail);
    }

    /**
     * @param $purchaseOrderDetail
     */
    protected function findAdditional(&$purchaseOrderDetail)
    {
        /*
         * Add these fields from InboundOrderDetail_Additional
            | location           | varchar(85)  | YES  |     | NULL    |       |
         */
        $additionals = InboundOrderDetailAdditional::whereObjectid($purchaseOrderDetail->objectID)->limit(20)->get();
        foreach ($additionals as $additional) {
            $purchaseOrderDetail->$additional['Name'] = $additional['Value'];
        }
        //dd($purchaseOrderDetail);

        return $purchaseOrderDetail;
    }

	protected function rawFilter($filter) {
		// Build a query based on filter $input
		$query = InboundOrderDetail::query()
            ->select('Inbound_Order_Detail.*')        // <- need this or you get select * which produces unexpected results when using join below
            ->orderBy('Inbound_Order_Detail.Order_Number', 'desc');
        foreach($filter as $key => $value) {
            if($key == 'objectID' && strlen($value) > 3) {
                $query->where('Inbound_Order_Detail.'.$key, 'like', $value.'%');        // <- do not need $query = $query->..
            }
            if($key == 'Order_Number' && strlen($value) > 3) {
                $query->where('Inbound_Order_Detail.'.$key, 'like', $value.'%');
            }
            elseif($key == 'SKU' && strlen($value) > 3) {
                $query->where('Inbound_Order_Detail.'.$key, 'like', $value.'%');
            }
            elseif($key == 'Expected_Qty' && strlen($value) > 3) {
                 $query->where('Inbound_Order_Detail.'.$key, 'like', $value.'%');
            }
            elseif($key == 'Actual_Qty' && strlen($value) > 3) {
                $query->where('Inbound_Order_Detail.'.$key, 'like', $value.'%');
            }
            elseif($key == 'Status' && is_array($value)) {
                //TODO this should be using ->whereIn(..)
                $query->whereRaw('Inbound_Order_Detail.'.$key." in ('".implode("','", $value)."')");
            }
            elseif($key == 'Status' && strlen($value) > 0) {
                $query->where('Inbound_Order_Detail.'.$key, 'like', $value.'%');
            }
            elseif($key == 'UPC' && strlen($value) > 4) {
                $query->where('Inbound_Order_Detail.'.$key, 'like', $value.'%');
            }
            elseif($key == 'UOM' && strlen($value) > 3) {
                $query->where('Inbound_Order_Detail.'.$key, 'like', $value.'%');
            }
            elseif($key == 'contains.UPC' && strlen($value) > 3) {
                $query
                    ->join('Item as Article', 'Article.objectID', '=', 'Inbound_Order_Detail.SKU')
                    ->join('itemKit', 'itemKit.parentID', '=', 'Article.objectID')
                    ->join('Item as UPC', 'UPC.objectID', '=', 'itemKit.objectID')
                    ->where(function ($query) use ($value) {
                        $query->where('UPC.objectID', '=', ltrim($value,'0'))
                            ->orWhere('UPC.Client_SKU', '=', ltrim($value,'0'));
                    });
                //dd(__METHOD__.'('.__LINE__.')',compact('filter','key','value','query'));
            }
            elseif($key == 'Location' && strlen($value) > 3) {
                // TODO this should be using joins as above
                $query->whereRaw("Inbound_Order_Detail.objectID in (select objectID from Inbound_Order_Detail_Additional where Name = 'Location' and Value = '$value' )");
            }
            else {
                //TODO Replace this with Eloquent Relationships, one to one|many
                preg_match('/purchaseOrder\.(.*)/', $key, $matches);
                if(count($matches) > 0) {
                    $subQueryWhereClause = " where Inbound_Order.$matches[1]" . (is_array($value) ? " in ('".implode("','", $value)."')" : " = '$value'");
                    //dd(compact('key','matches','value','subQueryWhereClause'));
                    // TODO this should be using joins as above
                    $query->whereRaw("Inbound_Order_Detail.Order_Number in (select distinct Inbound_Order.objectID from Inbound_Order $subQueryWhereClause )");
                }
                preg_match('/item\.(.*)/', $key, $matches);
                if(count($matches) > 0) {
                    //dd(compact('key','matches','value'));
                    /* $query->whereRaw("SKU in (select distinct Item.objectID from Item where Item.$matches[1] = '$value')");
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
                    //dd(compact('key', 'matches', 'value', 'subQueryWhereClause'));
                    $query->whereRaw("Inbound_Order_Detail.SKU in (select distinct Article.objectID  from Item as Article  left join itemKit on itemKit.parentID = Article.objectID  left join Item as UPC on UPC.objectID = itemKit.objectID $subQueryWhereClause )");
                }
            }
		}
        return $query;
    }

    /**
     * Implement filterOn($filter, $limit)
     */
    public function filterOn($filter, $limit=10) {

        if(isset($filter['THOU.container.Tote'])) {
            //dd(compact('input','limit'));
            return DB::connection('vitaldev')
                ->table('Generic_Container')
                ->join('container', 'container.parentID', '=', 'Generic_Container.objectID')
                ->join('Inventory', 'Inventory.objectID', '=', 'container.objectID')
                ->select('Inventory.Order_Line as objectID')
                ->where('Generic_Container.Carton_ID', $filter['THOU.container.Tote'])
                ->distinct()->get();
        }

        if($limit == 1) {
            return $this->findAdditional($this->rawFilter($filter)->first());
        } elseif($limit == 0) {
            $purchaseOrderDetails = $this->rawFilter($filter)->get();
        } else {
            $purchaseOrderDetails = $this->rawFilter($filter)->limit($limit)->get();
        }

        foreach($purchaseOrderDetails as $purchaseOrderDetail) {
            $this->findAdditional($purchaseOrderDetail);
        }

        return $purchaseOrderDetails;
    }

    /**
     * Implement paginate()
     */
    public function paginate($filter) {
        $purchaseOrderDetails = $this->rawFilter($filter)->paginate(10);

        foreach($purchaseOrderDetails as $purchaseOrderDetail) {
            $this->findAdditional($purchaseOrderDetail);
        }

        return $purchaseOrderDetails;
    }

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $pod = InboundOrderDetail::find($id);

        $updatedPod = $pod->update($input);

        //TODO find a way to automate which $name's are Additional, should not be hardcoded here.
        //TODO Found it!, if we subtract original attribute names from attributes, we get this list of Additional names
        $name = 'Location';
        //$newValue = $input[$name];

        if(isset($input[$name])) {
            $additional = InboundOrderDetailAdditional::where('objectID', $id)->where('Name', $name)->first();
            if(isset($additional)) {
                $additional->update(['Value' => $input[$name]]);
                $additional->save();
                //dd(compact('id','input','newValue','additional'));
                // Update does not appear to be working, deleting the record instead
                InboundOrderDetailAdditional::where('objectID', $id)->where('Name', $name)->delete();
            }
            if($input[$name] > 0) {
                InboundOrderDetailAdditional::create(['objectID' => $id, 'Name' => $name, 'Value' => $input[$name]]);
            }
        }

        return $updatedPod;
    }

}
