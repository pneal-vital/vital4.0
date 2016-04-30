<?php namespace vital3\Repositories;

use App\vital3\Container;
use App\vital3\Warehouse;
use App\vital3\Location;
use Illuminate\Support\Facades\DB;
use \Log;

class DBWarehouseRepository implements WarehouseRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return Warehouse::get();
        } elseif($limit == 1) {
            return Warehouse::first();
        }
		return Warehouse::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return Warehouse::findOrFail($id);
	}

    /**
     * desc Warehouse;
    +----------------+-------------+------+-----+---------+-------+
    | Field          | Type        | Null | Key | Default | Extra |
    +----------------+-------------+------+-----+---------+-------+
    | objectID       | bigint(20)  | NO   | PRI | NULL    |       |
    | Warehouse_Code | varchar(85) | YES  |     | NULL    |       |
    | Warehouse_Name | varchar(85) | YES  |     | NULL    |       |
    | Address_1      | varchar(85) | YES  |     | NULL    |       |
    | Address_2      | varchar(85) | YES  |     | NULL    |       |
    | City           | varchar(85) | YES  |     | NULL    |       |
    | Province       | varchar(85) | YES  |     | NULL    |       |
    | Post_Code      | varchar(85) | YES  |     | NULL    |       |
    | Phone          | varchar(85) | YES  |     | NULL    |       |
    | Fax            | varchar(85) | YES  |     | NULL    |       |
    | Remote_Address | varchar(85) | YES  |     | NULL    |       |
    +----------------+-------------+------+-----+---------+-------+
    11 rows in set (0.02 sec)
     * @param $filter
     * @return mixed
     */
	protected function rawFilter($filter) {
        //Log::debug('query: ',$filter);
		// Build a query based on filter $filter
        $orderBy = 'Warehouse_Name';
		$query = Warehouse::query()
            ->select('Warehouse.objectID', 'Warehouse.Warehouse_Code', 'Warehouse.Warehouse_Name', 'Warehouse.Address_1', 'Warehouse.Address_2', 'Warehouse.City', 'Warehouse.Province', 'Warehouse.Post_Code', 'Warehouse.Phone', 'Warehouse.Fax', 'Warehouse.Remote_Address');
		if(isset($filter['objectID']) && strlen($filter['objectID']) > 1) {
			$query->where('objectID', 'like', $filter['objectID'] . '%');
		}
		if(isset($filter['Warehouse_Code']) && strlen($filter['Warehouse_Code']) > 1) {
			$query->where('Warehouse_Code', 'like', $filter['Warehouse_Code'] . '%');
		}
		if(isset($filter['Warehouse_Name']) && strlen($filter['Warehouse_Name']) > 1) {
			$query->where('Warehouse_Name', 'like', $filter['Warehouse_Name'] . '%');
            $query->orderByRaw(DB::raw("concat(length(Warehouse_Name), Warehouse_Name)"));
            $orderBy = False;
		}
		if(isset($filter['objectID or Warehouse_Name']) && strlen($filter['objectID or Warehouse_Name']) > 1) {
            // s/b equivalent to where .. and (objectID = $locID or Warehouse_Name = $locID) and ..
            $locID = $filter['objectID or Warehouse_Name'];
			$query->where(function ($query) use ($locID) {
                $query->where('objectID', '=', $locID)->orWhere('Warehouse_Name', '=', $locID);
            });
		}
        if(isset($filter['Address_1']) && strlen($filter['Address_1']) > 1) {
            $query->where('Address_1', '=', $filter['Address_1']);
        }
        if(isset($filter['Address_2']) && strlen($filter['Address_2']) > 1) {
            $query->where('Address_2', '=', $filter['Address_2']);
        }
        if(isset($filter['City']) && strlen($filter['City']) > 1) {
            $query->where('City', '=', $filter['City']);
        }
        if(isset($filter['Province']) && strlen($filter['Province']) > 1) {
            $query->where('Province', 'like', $filter['Province'] . '%');
        }
        if(isset($filter['Post_Code']) && strlen($filter['Post_Code']) > 1) {
            $query->where('Post_Code', 'like', $filter['Post_Code'] . '%');
        }
        if(isset($filter['Fax']) && strlen($filter['Fax']) > 1) {
            $query->where('Fax', 'like', $filter['Fax'] . '%');
        }
        if(isset($filter['Remote_Address']) && strlen($filter['Remote_Address']) > 1) {
            $query->where('Remote_Address', 'like', $filter['Remote_Address'] . '%');
        }
        /*
         * container.child should generate this sql request
         * select Warehouse.* from Warehouse join container loc on loc.parentID = Warehouse.objectID where loc.objectID = 6213292075;
         */
        if(isset($filter['container.child']) && strlen($filter['container.child']) > 3) {
            $query
                ->join('container as loc', 'loc.parentID', '=', 'Warehouse.objectID')
                ->where('loc.objectID',$filter['container.child']);
        }
        if($orderBy) {
            $query->orderBy('Warehouse_Name', 'asc');
        }
        //dd(__METHOD__."(".__LINE__.")",compact('filter','query'));
        return $query;
    }

    /**
     * Implement filterOn($filter)
     */
    public function filterOn($filter, $limit=10) {
        if($limit == 0) {
            return $this->rawFilter($filter)->get();
        } elseif($limit == 1) {
            return $this->rawFilter($filter)->first();
        }
		return $this->rawFilter($filter)->limit($limit)->get();
	}

    /**
	 * Implement paginate($input)
	 */
	public function paginate($filter) {
		return $this->rawFilter($filter)->paginate(10);
	}

    /**
	 * Implement create($input)
	 */
	public function create($input) {
        Log::info('Create Warehouse', $input);
		return Warehouse::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $warehouse = Warehouse::find($id);

        //dd(__METHOD__.'('.__LINE__.')',compact('id','input'));
        Log::info("Update Warehouse $id", $input);
        return $warehouse->update($input);
    }

    /**
     * Implement delete($id)
     */
    public function delete($id) {
        $deleted = true;
        $warehouse = $this->find($id);

        if(isset($warehouse)) {
            //dd(__METHOD__.'('.__LINE__.')',compact('id','warehouse'));
            Log::info("Delete Warehouse $id");
            $deleted = $warehouse->delete();
        }

        return $deleted;
    }

    /**
     * Implement putLocationIntoWarehouse($locationID, $warehouseID)
     */
    public function putLocationIntoWarehouse($locationID, $warehouseID) {
        $location = Location::findOrFail($locationID);
        $warehouse = Warehouse::findOrFail($warehouseID);
        $container = DB::connection('vitaldev')
            ->table('container')
            ->where('objectID', $locationID)->first();

        Log::info("Put Location $locationID into Warehouse $warehouseID");
        if(isset($container)) {
            $result = DB::connection('vitaldev')
                ->table('container')
                ->where('containerID', $container->containerID)
                ->update(['parentID' => $warehouseID, 'objectID' => $locationID]);
            // $result === 1/true if the container was updated
            // $result === 0/false if no containers were updated
            if($result === 1 or $result === 0) return true;
        } else {
            $result = Container::create(['parentID' => $warehouseID, 'objectID' => $locationID]);
            // $result == container object created
            if(isset($result) and get_class($result) == 'App\vital3\Container') return true;
        }
        Log::error('putLocationIntoWarehouse failed');
        //dd(__METHOD__.'('.__LINE__.')',compact('locationID','warehouseID','location','warehouse','container','result'));
        return ['putLocationIntoWarehouse failed'];
    }

}
