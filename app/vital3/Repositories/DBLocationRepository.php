<?php namespace vital3\Repositories;

use App\vital3\Container;
use App\vital3\Location;
use App\vital3\Pallet;
use Illuminate\Support\Facades\DB;

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

	protected function rawFilter($filter) {
		// Build a query based on filter $filter
        $orderBy = 'Location_Name';
		$query = Location::query();
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
        if(isset($filter['container.parent']) && strlen($filter['container.parent']) > 3) {
            $query->whereRaw('objectID in (select objectID from container where parentID = '.$filter['container.parent'].')');
        }
        if(isset($filter['container.child']) && strlen($filter['container.child']) > 3) {
            $query->whereRaw('objectID in (select parentID from container where objectID = '.$filter['container.child'].')');
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
		return Location::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $location = Location::find($id);

        //dd($input);
        return $location->update($input);
    }

    /**
     * Implement putPalletIntoLocation($palletID, $locationID)
     */
    public function putPalletIntoLocation($palletID, $locationID) {
        $pallet = Pallet::findOrFail($palletID);
        $location = Location::findOrFail($locationID);
        $container = DB::connection('vitaldev')
            ->table('container')
            ->where('objectID', $palletID)->first();

        if(isset($container)) {
            DB::connection('vitaldev')
                ->table('container')
                ->where('containerID', $container->containerID)
                ->update(['parentID' => $locationID, 'objectID' => $palletID]);
        } else {
            Container::create(['parentID' => $locationID, 'objectID' => $palletID]);
        }
    }

}
