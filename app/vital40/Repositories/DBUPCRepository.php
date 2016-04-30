<?php namespace vital40\Repositories;

use App\vital3\Item;
use App\vital3\ItemKit;
use App\vital3\ItemAdditional;
use Illuminate\Support\Facades\DB;
use \Log;

class DBUPCRepository implements UPCRepositoryInterface {

    /**
     * Basic select to retrieve UPC data.
     * desc Item;
    +--------------------+--------------+------+-----+---------+-------+
    | Field              | Type         | Null | Key | Default | Extra |
    +--------------------+--------------+------+-----+---------+-------+
    | objectID           | bigint(20)   | NO   | PRI | NULL    |       |
    | Sku_Number         | varchar(85)  | YES  |     | NULL    |       |
    | Client_Code        | varchar(85)  | YES  | MUL | NULL    |       |
    | Client_SKU         | varchar(85)  | YES  | MUL | NULL    |       |
    | Description        | varchar(255) | YES  |     | NULL    |       |
    | UOM                | varchar(85)  | YES  |     | NULL    |       |
    | Per_Unit_Weight    | varchar(85)  | YES  |     | NULL    |       |
    | Retail_Price       | varchar(85)  | YES  |     | NULL    |       |
    | Case_Pack          | varchar(85)  | YES  |     | NULL    |       |
    | UPC                | varchar(85)  | YES  | MUL | NULL    |       |
    | Colour             | varchar(85)  | YES  |     | NULL    |       |
    | Zone               | varchar(85)  | YES  |     | NULL    |       |
    | Delivery_Number    | varchar(85)  | YES  | MUL | NULL    |       |
    | PO_Number          | varchar(85)  | YES  | MUL | NULL    |       |
    | Description_2      | varchar(255) | YES  |     | NULL    |       |
    | Vendor_Item_Number | varchar(85)  | YES  |     | NULL    |       |
    | Cases_Ordered      | varchar(85)  | YES  |     | NULL    |       |
    | Master_Pack_Cube   | varchar(85)  | YES  |     | NULL    |       |
    | Master_Pack_Weight | varchar(85)  | YES  |     | NULL    |       |
    | Total_Weight       | varchar(85)  | YES  |     | NULL    |       |
    | Total_Cube         | varchar(85)  | YES  |     | NULL    |       |
    +--------------------+--------------+------+-----+---------+-------+
    21 rows in set (0.00 sec)
     * @return mixed
     */
	private function upcSelect() {
		// Using QueryBuilder joins
        return Item::from( 'Item as UPC' )
            ->select('UPC.objectID', 'UPC.Sku_Number', 'UPC.Client_Code', 'UPC.Client_SKU', 'UPC.Description', 'UPC.UOM'
                   , 'UPC.Per_Unit_Weight', 'UPC.Retail_Price', 'UPC.Case_Pack', 'UPC.UPC', 'UPC.Colour', 'UPC.Zone'
                   , 'UPC.Delivery_Number', 'UPC.PO_Number', 'UPC.Description_2', 'UPC.Vendor_Item_Number', 'UPC.Cases_Ordered'
                   , 'UPC.Master_Pack_Cube', 'UPC.Master_Pack_Weight', 'UPC.Total_Weight', 'UPC.Total_Cube')
            ->join('itemKit', 'itemKit.objectID', '=', 'UPC.objectID')
            ->distinct();
	}

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return $this->findAdditional($this->upcSelect()->get());
        } elseif($limit == 1) {
            return $this->findAdditional($this->upcSelect()->first());
        }
		return $this->findAdditional($this->upcSelect()->limit($limit)->get());
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
        /*
         * The important thing to learn here is that DB:conn .. ->get() returns an array of lines.
         * find function should only find one, so return the $upcs[0];
         * or use first() and return $upc;
         */
		$upc = $this->upcSelect()
			->where('UPC.objectID', '=', $id)
			->first();

        $this->findAdditional($upc);

        //dd(__METHOD__.'('.__LINE__.')',compact('id','upc'));
		return $upc;
	}

    private function findAdditional($container) {
        /*
         * Here we want to change the contents within the $container, not the $container,
         * so retrieve/setup $items as an array_expression holding those contents
         */
        $items = $container;
        // if $container is a paginator
        if(is_a($container, 'Illuminate\Pagination\LengthAwarePaginator')) {
            $items = $container->items();
        }
        // if $items is not, make it an array
        elseif(!is_array($container) && !is_a($container, 'Illuminate\Support\Collection')) {
            $items = array($container);
        }
        //dd(__METHOD__."(".__LINE__.")",compact('container','items'));
        //Log::debug(__METHOD__."(".__LINE__."):  container class: ".get_class($container));
        //Log::debug(__METHOD__."(".__LINE__."):  items class: ".(is_array($items) ? "array(".count($items).")" : get_class($items)));

        foreach($items as $upc) {
            if(isset($upc)) {  // could be null if find($id) did not find an article
                /*
                 * Add these fields from Item_Additional
                    | rework             | varchar(85)  | YES  |     | NULL    |       |
                 */
                $additionals = ItemAdditional::where('objectID', $upc->objectID)->get();
                foreach($additionals as $additional) {
                    $upc->$additional['Name'] = $additional['Value'];
                }
                /*
                 * Add these fields from Article
                    | parentID           | varchar(85)  | YES  |     | NULL    |       |
                    | parentSKU          | varchar(85)  | YES  |     | NULL    |       |
                    | Quantity           | bigint(20)   | YES  |     | NULL    |       |
                 */
                $articles = Item::from( 'Item as Article' )
                    ->join('itemKit', 'itemKit.parentID', '=', 'Article.objectID')
                    ->select('Article.objectID', 'Article.Client_SKU', 'itemKit.Quantity')
                    ->distinct()
                    ->where('itemKit.objectID', '=', $upc->objectID)
                    ->get();
                foreach($articles as $article) {
                    $parents = $upc->parents;
                    $parents[$article->objectID] = (object) [
                        'parentID'  => $article->objectID,
                        'parentSKU' => $article->Client_SKU,
                        'Quantity'  => $article->Quantity,
                    ];
                    $upc->parents = $parents;
                }
                //dd(__METHOD__.'('.__LINE__.')',compact('container','items','upc','additionals','articles','parents'));
            }
        }

        return $container;
    }


    protected function rawFilter($filter) {
        //Log::debug('query: ',$filter);
		// Build a query based on filter $input
		$query = $this->upcSelect();
		if(isset($filter['objectID']) && strlen($filter['objectID']) > 3) {
			$query->where('UPC.objectID', '=', $filter['objectID']);
		}
		if(isset($filter['Sku_Number']) && strlen($filter['Sku_Number']) > 3) {
			$query->where('UPC.Sku_Number', 'like', ltrim($filter['Sku_Number'],'0') . '%');
		}
        if(isset($filter['Client_Code']) && strlen($filter['Client_Code']) > 3) {
            $query->where('UPC.Client_Code', $filter['Client_Code']);
        }
		if(isset($filter['Client_SKU']) && strlen($filter['Client_SKU']) > 3) {
			$query->where('UPC.Client_SKU', 'like', ltrim($filter['Client_SKU'],'0') . '%');
		}
		if(isset($filter['Description']) && strlen($filter['Description']) > 3) {
			$query->where('UPC.Description', 'like', $filter['Description'] . '%');
		}
		if(isset($filter['UPC']) && strlen($filter['UPC']) > 3) {
			$query->where('UPC.UPC', 'like', ltrim($filter['UPC'],'0') . '%');
		}
        if(isset($filter['UOM']) && strlen($filter['UOM']) > 3) {
            $query->where('UPC.UOM', '=', $filter['UOM']);
        }
		if(isset($filter['Colour']) && strlen($filter['Colour']) > 1) {
			$query->where('UPC.Colour', 'like', $filter['Colour'] . '%');
		}
		if(isset($filter['Zone']) && strlen($filter['Zone']) > 3) {
			$query->where('UPC.Zone', 'like', $filter['Zone'] . '%');
		}
        return $query;
    }

    /**
     * Implement filterOn($filter, $limit)
     */
    public function filterOn($filter, $limit=10) {
        if($limit == 0) {
            return $this->findAdditional($this->rawFilter($filter)->get());
        } elseif($limit == 1) {
            return $this->findAdditional($this->rawFilter($filter)->first());
        }
		return $this->findAdditional($this->rawFilter($filter)->limit($limit)->get());
	}

    /**
     * Implement paginate($filter)
     */
    public function paginate($filter) {
        $results = $this->rawFilter($filter)->paginate(10);
        $this->findAdditional($results);
        //dd(__METHOD__.'('.__LINE__.')',compact('filter', 'results'));
        return $results;
	}

	/**
	 * Get the UPCs with this Article ID
	 * @param $articleID
	 * @return mixed
     */
	public function getArticleUPCs($articleID, $limit=10) {
        $query = $this->upcSelect()
            ->join('Item as Article', 'Article.objectID', '=', 'itemKit.parentID')
			->where('itemKit.parentID', '=', $articleID)
			->orWhere('Article.Client_SKU', '=', $articleID);
        if($limit == 0) {
            return $this->findAdditional($query->get());
        } elseif($limit == 1) {
            return $this->findAdditional($query->first());
        }
        return $this->findAdditional($query->limit($limit)->get());
	}

	/**
	 * Get the UPCs with this Article ID
	 * @param $articleID
	 * @return mixed
     */
	public function paginateArticleUPCs($articleID) {
        $query = $this->upcSelect()
			->where('itemKit.parentID', '=', $articleID);
        return $this->findAdditional($query->paginate(10));
	}

    /**
     * Get the UPCs with this Tote ID
     * @param $toteID
     * @return mixed
     */
    public function getToteUPCs($toteID, $limit=10) {
        $query = $this->upcSelect()
            ->join('Inventory', 'Inventory.Item', '=', 'UPC.objectID')
            ->join('container as inv', 'inv.objectID', '=', 'Inventory.objectID')
            ->join('Generic_Container', 'Generic_Container.objectID', '=', 'inv.parentID')
            ->where('Generic_Container.objectID', '=', $toteID)
            ->orWhere('Generic_Container.Carton_ID', '=', $toteID);
        if($limit == 0) {
            return $this->findAdditional($query->get());
        } elseif($limit == 1) {
            return $this->findAdditional($query->first());
        }
        return $this->findAdditional($query->limit($limit)->get());
    }

    /**
     * In this case, we may be asked for a specific UPC, the UPCs in a tote, or the UPCs of an article.
     * We must be concerned with the comingling flag, when yes, take all UPCs for the article.
     * @param $filter
     * @return array( UPCs )
     */
    public function combine($filter)
    {
        $UPCs = [];
        if(isset($filter['upcID'])) {
            $upcData = $this->filterOn(['objectID' => $filter['upcID']]);
            foreach($upcData as $upc) {
                $UPCs[$upc->objectID] = $upc;
            }
            $upcData = $this->filterOn(['Client_SKU' => $filter['upcID']]);
            foreach($upcData as $upc) {
                $UPCs[$upc->objectID] = $upc;
            }
        }
        elseif(isset($filter['toteID'])) {
            $upcData = $this->getToteUPCs($filter['toteID'], 0);
            foreach($upcData as $upc) {
                $UPCs[$upc->objectID] = $upc;
            }
        }
        elseif(isset($filter['articleID'])) {
            $upcData = $this->getArticleUPCs($filter['articleID'], 0);
            //dd(__METHOD__.'('.__LINE__.')',compact('UPCs','filter','upcData'));
            foreach($upcData as $upc) {
                $UPCs[$upc->objectID] = $upc;
            }
        }
        Log::debug(__METHOD__.'('.__LINE__.'):  UPC count: '.count($UPCs));
        //dd(__METHOD__.'('.__LINE__.')', compact('filter','upcData', 'UPCs'));

        // Comingling flag, when yes (split == 'N', default to split), take all UPCs for the article.
        if(count($UPCs)) {
            $parentIDs = [];
            foreach($UPCs as $upc) {
                if(in_array($upc->parents, $parentIDs) == False) {
                    $parentIDs = array_merge($parentIDs, array_keys($upc->parents));
                }
            }
            $parentIDs = array_unique($parentIDs);
            $comingledIDs = DB::connection('vitaldev')
                ->table('Item_Additional')
                ->select('Item_Additional.objectID')
                ->whereIn('Item_Additional.objectID', $parentIDs)
                ->where('Item_Additional.Name', 'split')
                ->where('Item_Additional.Value', 'N')
                ->get();
            //dd(__METHOD__.'('.__LINE__.')', compact('filter', 'UPCs', 'parentIDs', 'comingledIDs'));
            if(count($comingledIDs)) {
                $articleIDs = [];
                foreach($comingledIDs as $comingledID) {
                    if(in_array($comingledID->objectID, $articleIDs) == False) {
                        $articleIDs[] = $comingledID->objectID;
                    }
                }
                $upcData = $this->upcSelect()
                    ->whereIn('itemKit.parentID', $articleIDs)
                    ->get();
                //dd(__METHOD__.'('.__LINE__.')', compact('filter', 'UPCs', 'parentIDs', 'comingledIDs', 'articleIDs', 'upcData'));
                foreach($upcData as $upc) {
                    $UPCs[$upc->objectID] = $upc;
                }
            }
        }
        //dd(__METHOD__.'('.__LINE__.')', compact('filter', 'UPCs', 'parentIDs', 'comingledIDs', 'articleIDs'));

        return $UPCs;
    }

    /**
	 * Implement create($input)
	 */
	public function create($input) {
        $upc = Item::create($input);
        ItemKit::create(['parentID' => '0', 'objectID' => $upc->objectID, 'Quantity' => '0']);

        // update here to add the _additional attributes
        //$updatedUPC = $this->update($upc->objectID, $input);

        //dd(__METHOD__.'('.__LINE__.')',compact('input','upc','updatedUPC'));
		return $upc;
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$item = $this->find($id);

        $updatedUPC = $item->update($input);
        //dd(__METHOD__.'('.__LINE__.')',compact('id', 'input', 'item','updatedItem'));

        return $updatedUPC;
	}

    /**
     * Implement delete($id)
     */
    public function delete($id) {
        $deleted = true;
        $item = $this->find($id);

        if(isset($item)) {
            //dd(__METHOD__.'('.__LINE__.')',compact('id','item'));
            ItemKit::where('objectID', $item->objectID)->delete();
            ItemAdditional::destroy($item->objectID);
            $deleted = $item->delete();
        }

        return $deleted;
    }

}
