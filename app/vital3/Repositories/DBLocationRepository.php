<?php namespace vital3\Repositories;

use App\vital3\Container;
use App\vital3\Location;
use App\vital3\Pallet;
use Illuminate\Support\Facades\DB;
use \Log;

class DBLocationRepository implements LocationRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return Location::get();
        } elseif($limit == 1) {
            return Location::first();
        }
		return Location::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return Location::findOrFail($id);
	}

    /**
     * desc Location;
    +---------------+-------------+------+-----+---------+-------+
    | Field         | Type        | Null | Key | Default | Extra |
    +---------------+-------------+------+-----+---------+-------+
    | objectID      | bigint(20)  | NO   | PRI | NULL    |       |
    | Location_Name | varchar(85) | YES  | MUL | NULL    |       |
    | Capacity      | varchar(85) | YES  |     | NULL    |       |
    | x             | varchar(85) | YES  |     | NULL    |       |
    | y             | varchar(85) | YES  |     | NULL    |       |
    | z             | varchar(85) | YES  |     | NULL    |       |
    | Status        | varchar(85) | YES  |     | NULL    |       |
    | LocType       | varchar(85) | YES  | MUL | NULL    |       |
    | Comingle      | varchar(85) | YES  |     | NULL    |       |
    | ChargeType    | varchar(85) | YES  |     | NULL    |       |
    +---------------+-------------+------+-----+---------+-------+
    10 rows in set (0.00 sec)
     * @param $filter
     * @return mixed
     */
	protected function rawFilter($filter) {
        //Log::debug('query: ',$filter);
		// Build a query based on filter $filter
        $orderBy = 'Location_Name';
		$query = Location::query()
            ->select('Location.objectID', 'Location.Location_Name', 'Location.Capacity', 'Location.x', 'Location.y', 'Location.z', 'Location.Status', 'Location.LocType', 'Location.Comingle', 'Location.ChargeType');
		if(isset($filter['objectID']) && strlen($filter['objectID']) > 1) {
			$query->where('objectID', 'like', $filter['objectID'] . '%');
		}
		if(isset($filter['Location_Name']) && strlen($filter['Location_Name']) > 1) {
			$query->where('Location_Name', 'like', $filter['Location_Name'] . '%');
            $query->orderByRaw(DB::raw("concat(length(Location_Name), Location_Name)"));
            $orderBy = False;
		}
		if(isset($filter['objectID or Location_Name']) && strlen($filter['objectID or Location_Name']) > 1) {
            // s/b equivalent to where .. and (objectID = $locID or Location_Name = $locID) and ..
            $locID = $filter['objectID or Location_Name'];
			$query->where(function ($query) use ($locID) {
                $query->where('objectID', '=', $locID)->orWhere('Location_Name', '=', $locID);
            });
		}
        if(isset($filter['x']) && strlen($filter['x']) > 0) {
            $query->where('x', '=', $filter['x']);
        }
        if(isset($filter['y']) && strlen($filter['y']) > 0) {
            $query->where('y', '=', $filter['y']);
        }
        if(isset($filter['z']) && strlen($filter['z']) > 0) {
            $query->where('z', '=', $filter['z']);
        }
        if(isset($filter['LocType']) && strlen($filter['LocType']) > 1) {
            $query->where('LocType', 'like', $filter['LocType'] . '%');
        }
        if(isset($filter['Comingle']) && strlen($filter['Comingle']) > 0) {
            $query->where('Comingle', 'like', $filter['Comingle'] . '%');
        }
        /*
         * container.parent should generate this sql request
         * select Location.* from Location join container loc on loc.objectID = Location.objectID where loc.parentID = 6213292055;
         */
        if(isset($filter['container.parent']) && strlen($filter['container.parent']) > 3) {
            $query
                ->join('container as loc', 'loc.objectID', '=', 'Location.objectID')
                ->where('loc.parentID',$filter['container.parent']);
        }
        /*
         * container.child should generate this sql request
         * select Location.* from Location join container plt on plt.parentID = Location.objectID where plt.objectID = 6213292075;
         */
        if(isset($filter['container.child']) && strlen($filter['container.child']) > 3) {
            $query
                ->join('container as plt', 'plt.parentID', '=', 'Location.objectID')
                ->where('plt.objectID',$filter['container.child']);
        }
        if($orderBy) {
            $query->orderBy('Location_Name', 'asc');
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
        Log::info('Create Location', $input);
        return Location::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $location = Location::find($id);

        //dd(__METHOD__.'('.__LINE__.')',compact('id','input'));
        Log::info("Update Location $id", $input);
        return $location->update($input);
    }

    /**
     * Implement delete($id)
     */
    public function delete($id) {
        $deleted = true;
        $location = $this->find($id);

        if(isset($location)) {
            //dd(__METHOD__.'('.__LINE__.')',compact('id','location'));
            Log::info("Delete Location $id");
            $deleted = $location->delete();

            // delete the container object also
            DB::connection('vitaldev')
                ->statement('delete from container where objectID = '.$id);
        }

        return $deleted;
    }

    /**
     * IMPORTANT: Call this function name on the Controller to verify this action is allowed
     * Implement putPalletIntoLocation($palletID, $locationID)
     */
    public function putPalletIntoLocation($palletID, $locationID) {
        $pallet = Pallet::findOrFail($palletID);
        $location = Location::findOrFail($locationID);
        $container = DB::connection('vitaldev')
            ->table('container')
            ->where('objectID', $palletID)->first();

        Log::info("Put Pallet $palletID into Location $locationID");
        if(isset($container)) {
            $result = DB::connection('vitaldev')
                ->table('container')
                ->where('containerID', $container->containerID)
                ->update(['parentID' => $locationID, 'objectID' => $palletID]);
            // $result === 1/true if the container was updated
            // $result === 0/false if no containers were updated
            if($result === 1 or $result === 0) return true;
        } else {
            $result = Container::create(['parentID' => $locationID, 'objectID' => $palletID]);
            // $result == container object created
            if(isset($result) and get_class($result) == 'App\vital3\Container') return true;
        }
        Log::error('putPalletIntoLocation failed');
        //dd(__METHOD__.'('.__LINE__.')',compact('palletID','locationID','pallet','location','container','result'));
        return ['putPalletIntoLocation failed'];
    }

}
