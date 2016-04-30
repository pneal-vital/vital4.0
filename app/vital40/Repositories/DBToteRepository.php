<?php namespace vital40\Repositories;

use App\vital3\Container;
use App\vital3\Inventory;
use App\vital3\GenericContainer;
use App\vital3\GenericContainerAdditional;
use Illuminate\Support\Facades\DB;
use \Config;
use \Log;

class DBToteRepository implements ToteRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return GenericContainer::get();
        } elseif($limit == 1) {
            return GenericContainer::first();
        }
		return GenericContainer::limit(10)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return GenericContainer::findOrFail($id);
	}

    /**
     * desc Generic_Container;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | NULL    |       |
    | Carton_ID | varchar(85) | YES  | MUL | NULL    |       |
    | Status    | varchar(85) | YES  |     | OPEN    |       |
    +-----------+-------------+------+-----+---------+-------+
    3 rows in set (0.01 sec)
     * @param $filter
     * @return mixed
     */
	protected function rawFilter($filter) {
        //Log::debug('query: ',$filter);
        // Build a query based on filter $input
        $query = GenericContainer::query()
            ->select('Generic_Container.objectID', 'Generic_Container.Carton_ID', 'Generic_Container.Status')
            ->orderBy('Carton_ID', 'asc');
        if(isset($filter['objectID']) && strlen($filter['objectID']) > 3) {
            $query->where('Generic_Container.objectID', 'like', $filter['objectID'] . '%');
        }
        if(isset($filter['Carton_ID']) && strlen($filter['Carton_ID']) > 3) {
            $query->where('Generic_Container.Carton_ID', 'like', ltrim($filter['Carton_ID'],'0') . '%');
        }
        if(isset($filter['Status']) && is_array($filter['Status'])) {
            $query->whereRaw("Generic_Container.Status in ('".implode("','", $filter['Status'])."')");
        }
        elseif(isset($filter['Status']) && strlen($filter['Status']) > 3) {
            $query->where('Generic_Container.Status', '=', $filter['Status']);
        }
        //dd(__METHOD__."(".__LINE__.")",compact('filter','query'));
        /*
         * container.parent should generate this sql request
         * select Generic_Container.* from Generic_Container join container gc on gc.objectID = Generic_Container.objectID where gc.parentID = 6232065627;
         */
        if(isset($filter['container.parent']) && strlen($filter['container.parent']) > 3) {
            $query
                ->join('container as gc', 'gc.objectID', '=', 'Generic_Container.objectID')
                ->where('gc.parentID',$filter['container.parent']);
        }
        /*
         * container.child should generate this sql request
         * select Generic_Container.* from Generic_Container join container inv on inv.parentID = Generic_Container.objectID where inv.objectID = 6232067838;
         */
        if(isset($filter['container.child']) && strlen($filter['container.child']) > 3) {
            $query
                ->join('container as inv', 'inv.parentID', '=', 'Generic_Container.objectID')
                ->where('inv.objectID',$filter['container.child']);
        }
        if(isset($filter['upcID']) && strlen($filter['upcID']) > 3) {
            $query
                ->join('container as inv', 'inv.parentID', '=', 'Generic_Container.objectID')
                ->join('Inventory', 'Inventory.objectID', '=', 'inv.objectID')
                ->where('Inventory.Item', $filter['upcID']);
        }
        // TODO remove this if block, check if it is being used.
        if(isset($filter['Location.parent']) && strlen($filter['Location.parent']) > 3) {
            $query->whereRaw('objectID in (select gc.objectID from container gc  join container plt on plt.objectID = gc.parentID  where plt.parentID = '.$filter['Location.parent'].')');
        }
        if(isset($filter['locationID']) && strlen($filter['locationID']) > 3) {
            $query
                ->join('container as gc', 'gc.objectID', '=', 'Generic_Container.objectID')
                ->join('container as plt', 'plt.objectID', '=', 'gc.parentID')
                ->where('plt.parentID', $filter['locationID']);
        }
        return $query;
	}

	/**
	 * Implement filterOn($filter, $limit)
	 */
	public function filterOn($filter, $limit=10) {

        if(isset($filter['THOU.container.child'])) {
            //TODO remove this, use the improved $filter['container.child']
            return DB::connection(GenericContainer::CONNECTION_NAME)
                ->table('Generic_Container')
                ->join('container as inv', 'inv.parentID', '=', 'Generic_Container.objectID')
                ->select('Generic_Container.objectID', 'Generic_Container.Carton_ID', 'Generic_Container.Status')
                ->where('inv.objectID', $filter['THOU.container.child'])
                ->get();
        }

        if(isset($filter['THOU.Location.parent'])) {
            return DB::connection(GenericContainer::CONNECTION_NAME)
                ->table('Generic_Container')
                ->join('container as gc', 'gc.objectID', '=', 'Generic_Container.objectID')
                ->join('container as plt', 'plt.objectID', '=', 'gc.parentID')
                ->select('Generic_Container.objectID', 'Generic_Container.Carton_ID', 'Generic_Container.Status')
                ->where('plt.parentID', $filter['THOU.Location.parent'])
                ->get();
        }

        if(isset($filter['THOU.locID_and_podID'])) {
            return DB::connection(GenericContainer::CONNECTION_NAME)
                ->table('container as plt')
                ->join('container as gc', 'gc.parentID', '=', 'plt.objectID')
                ->join('Generic_Container', 'Generic_Container.objectID', '=', 'gc.objectID')
                ->join('container as inv', 'inv.parentID', '=', 'gc.objectID')
                ->join('Inventory', 'Inventory.objectID', '=', 'inv.objectID')
                ->select('Generic_Container.objectID', 'Generic_Container.Carton_ID', 'Generic_Container.Status')
                ->where('plt.parentID', $filter['THOU.locID_and_podID'][0])
                ->where('Inventory.Order_Line', $filter['THOU.locID_and_podID'][1])
                ->get();
        }

        if(isset($filter['THOU.locID_not_podID'])) {
            return DB::connection(GenericContainer::CONNECTION_NAME)
                ->table('container as plt')
                ->join('container as gc', 'gc.parentID', '=', 'plt.objectID')
                ->join('Generic_Container', 'Generic_Container.objectID', '=', 'gc.objectID')
                ->join('container as inv', 'inv.parentID', '=', 'gc.objectID')
                ->join('Inventory', 'Inventory.objectID', '=', 'inv.objectID')
                ->select('Generic_Container.objectID', 'Generic_Container.Carton_ID', 'Generic_Container.Status')
                ->where('plt.parentID', '=', $filter['THOU.locID_not_podID'][0])
                ->where('Inventory.Order_Line', '!=', $filter['THOU.locID_not_podID'][1])
                ->get();
        }

        if($limit == 0) {
            return $this->rawFilter($filter)->get();
        } elseif($limit == 1) {
            return $this->rawFilter($filter)->first();
        }
        return $this->rawFilter($filter)->limit($limit)->get();
	}

    /**
     * Implement paginate($filter)
     */
    public function paginate($filter) {
        return $this->rawFilter($filter)->paginate(10);
	}

    /**
     * @param $filter - because they provide a new Carton_ID to this method
     * @return mixed - Tote
     */
    public function findOrCreate($filter) {
        $tote = $this->filterOn($filter, 1);
        Log::debug('tote: '.(isset($tote) ? $tote->Carton_ID : "null"));
        // if we didn't find one, do we want to create one?
        if(!isset($tote) && isset($filter['Carton_ID']) && strlen($filter['Carton_ID']) > 3) {
            // is it the Carton_ID format correct?
            $regex = '/^\d{2} \d{4} \d{4}$/';
            if(preg_match($regex, $filter['Carton_ID'])) {
                if(!isset($filter['Status']) || strlen($filter['Status']) == 0) {
                    $filter['Status'] = Config::get('constants.tote.status.open');
                }
                Log::info('Create Tote',$filter);
                $tote = $this->create($filter);
            }
        }
        return $tote;
    }

    /**
	 * Implement create($input)
	 */
	public function create($input) {
        Log::info('Create Tote', $input);
		return GenericContainer::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$tote = GenericContainer::find($id);

		//dd(__METHOD__.'('.__LINE__.')',compact('id','input','tote'));
        Log::info("Update Tote $id", $input);
		return $tote->update($input);
	}

    /**
     * Implement delete($id)
     */
    public function delete($id) {
        $deleted = true;
        $tote = $this->find($id);

        if(isset($tote)) {
            //dd(__METHOD__.'('.__LINE__.')',compact('id','tote'));
            Log::info("Delete Tote $id");
            $deleted = $tote->delete();

            // delete the container object also
            DB::connection(GenericContainer::CONNECTION_NAME)
                ->statement('delete from container where objectID = '.$id);
        }

        return $deleted;
    }

    /**
     * Implement getAdditional($id)
     */
    public function getAdditional($id) {
        // TODO Generic_Container_Additionl values need to be retrieved, and update-able.
        // using the Eloquent model
        return GenericContainerAdditional::whereObjectid($id)->limit(20)->get();
    }

    /**
     * IMPORTANT: Call this function name on the Controller to verify this action is allowed
     * Implement putInventoryIntoTote($inventoryID, $toteID)
     */
    public function putInventoryIntoTote($inventoryID, $toteID) {
        $inventory = Inventory::findOrFail($inventoryID);
        $tote = GenericContainer::findOrFail($toteID);
        $container = DB::connection(GenericContainer::CONNECTION_NAME)
            ->table('container')
            ->where('objectID', $inventoryID)->first();

        Log::info("Put Inventory $inventoryID into Tote $toteID");
        if(isset($container)) {
            $result = DB::connection(GenericContainer::CONNECTION_NAME)
                ->table('container')
                ->where('containerID', $container->containerID)
                ->update(['parentID' => $toteID, 'objectID' => $inventoryID]);
            // $result === 1/true if the container was updated
            // $result === 0/false if no containers were updated
            if($result === 1 or $result === 0) return true;
        } else {
            $result = Container::create(['parentID' => $toteID, 'objectID' => $inventoryID]);
            // $result == container object created
            if(isset($result) and get_class($result) == 'App\vital3\Container') return true;
        }
        Log::error('putInventoryIntoTote failed');
        //dd(__METHOD__.'('.__LINE__.')',compact('inventoryID','toteID','inventory','tote','container','result'));
        return ['putInventoryIntoTote failed'];
    }

    public function openToteContents($locationID, $podID) {
        /*
            select coalesce(Generic_Container.objectID,'') as toteID, coalesce(Carton_ID,''), UPC.objectID as upcID, UPC.Client_SKU as UPC, coalesce(Inventory.Quantity,0)
              from Inbound_Order_Detail
              join Item Article on Article.objectID = Inbound_Order_Detail.SKU
              join itemKit on itemKit.parentID = Article.objectID
              join Item UPC on UPC.objectID = itemKit.objectID
              left join Inventory on Inventory.Order_Line = Inbound_Order_Detail.objectID and Inventory.Item = UPC.objectID
              left join container inv on inv.objectID = Inventory.objectID
              left join container gc on gc.objectID = inv.parentID
              left join Generic_Container on Generic_Container.objectID = gc.objectID
             where Inbound_Order_Detail.objectID = 6232065755;
            +------------+------------------------+------------+-------------+--------------------------------+
            | toteID     | coalesce(Carton_ID,'') | upcID      | UPC         | coalesce(Inventory.Quantity,0) |
            +------------+------------------------+------------+-------------+--------------------------------+
            | 6232066022 | 52 0037 4479           | 6214202822 | 63664318010 | 6                              |
            | 6232066022 | 52 0037 4479           | 6214202826 | 63664318027 | 12                             |
            | 6232066022 | 52 0037 4479           | 6214202830 | 63664318034 | 1                              |
            | 6232066022 | 52 0037 4479           | 6214202834 | 63664318041 | 14                             |
            |            |                        | 6214202838 | 63664318058 | 0                              |
            +------------+------------------------+------------+-------------+--------------------------------+
            5 rows in set (2.31 sec)
         */
        $upcData = DB::connection(GenericContainer::CONNECTION_NAME)
            ->table('Inbound_Order_Detail')
            ->join('Item as Article', 'Article.objectID', '=', 'Inbound_Order_Detail.SKU')
            ->join('itemKit', 'itemKit.parentID', '=', 'Article.objectID')
            ->join('Item as UPC', 'UPC.objectID', '=', 'itemKit.objectID')
            ->leftJoin('Inventory', 'Inventory.Order_Line', '= Inbound_Order_Detail.objectID and Inventory.Status = \'RECD\' and Inventory.Item =', 'UPC.objectID')
            ->leftJoin('container as inv', 'inv.objectID', '=', 'Inventory.objectID')
            ->leftJoin('container as gc', 'gc.objectID', '=', 'inv.parentID')
            ->leftJoin('Generic_Container', 'Generic_Container.objectID', '=', 'gc.objectID')
            ->select('Generic_Container.objectID as toteID', 'Carton_ID', 'UPC.objectID as upcID', 'UPC.Client_SKU', 'Inventory.Quantity')
            ->where('Inbound_Order_Detail.objectID', '=', $podID)
            ->distinct()->get();

        foreach($upcData as $upc) {
            if(!isset($upc->toteID))  $upc->toteID = '';
            if(!isset($upc->Carton_ID))  $upc->Carton_ID = '';
            if(!isset($upc->Quantity))  $upc->Quantity = 0;
        }

        return $upcData;
    }

    public function isEmpty($toteID) {
        $container = DB::connection(GenericContainer::CONNECTION_NAME)
            ->table('container')
            ->where('parentID', $toteID)->first();
        return isset($container) == false;
    }

}
