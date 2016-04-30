<?php namespace vital3\Repositories;

use App\vital3\Container;
use App\vital3\GenericContainer;
use App\vital3\Pallet;
use Illuminate\Support\Facades\DB;
use \Config;
use \Log;

class DBPalletRepository implements PalletRepositoryInterface {

    protected $locationRepository;

    /**
     * Constructor requires article Repository
     */
    public function __construct(
        LocationRepositoryInterface $locationRepository
    ) {
        $this->locationRepository = $locationRepository;
    }

    /**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return Pallet::get();
        } else if($limit == 1) {
            return Pallet::first();
        }
		return Pallet::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return Pallet::findOrFail($id);
	}

    /**
     * desc Pallet;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | 0       |       |
    | Pallet_ID | varchar(85) | NO   | MUL |         |       |
    | x         | varchar(85) | NO   |     |         |       |
    | y         | varchar(85) | NO   |     |         |       |
    | z         | varchar(85) | NO   |     |         |       |
    | Status    | varchar(85) | NO   |     |         |       |
    +-----------+-------------+------+-----+---------+-------+
    6 rows in set (0.01 sec)
     * @param $filter
     * @return mixed
     */
	protected function rawFilter($filter) {
        //Log::debug('query: ',$filter);
		// Build a query based on filter $filter
		$query = Pallet::query()
            ->select('Pallet.objectID', 'Pallet.Pallet_ID', 'Pallet.x', 'Pallet.y', 'Pallet.z', 'Pallet.Status')
            ->orderBy('Pallet_ID', 'asc');
        if(isset($filter['Pallet_ID']) && strlen($filter['Pallet_ID']) > 2) {
            $query->where('Pallet_ID', 'like', ltrim($filter['Pallet_ID'],'0') . '%');
		}
		if(isset($filter['Pallet_ID.prefix']) && is_array($filter['Pallet_ID.prefix'])) {
            $query->whereRaw("substring(Pallet_ID,1,3) in ('".implode("','", $filter['Pallet_ID.prefix'])."')");
		}
        if(isset($filter['Status']) && is_array($filter['Status'])) {
            $query->whereRaw("Status in ('".implode("','", $filter['Status'])."')");
        }
        elseif(isset($filter['Status']) && strlen($filter['Status']) > 3) {
            $query->where('Status', '=', $filter['Status']);
        }
        /*
         * container.parent should generate this sql request
         * select Pallet.* from Pallet join container plt on plt.objectID = Pallet.objectID where plt.parentID = 6213292055;
         */
        if(isset($filter['container.parent']) && strlen($filter['container.parent']) > 3) {
            $query
                ->join('container as plt', 'plt.objectID', '=', 'Pallet.objectID')
                ->where('plt.parentID',$filter['container.parent']);
        }
        /*
         * container.child should generate this sql request
         * select Pallet.* from Pallet join container gc on gc.parentID = Pallet.objectID where gc.objectID = 6226111054;
         */
        if(isset($filter['container.child']) && strlen($filter['container.child']) > 3) {
            $query
                ->join('container as gc', 'gc.parentID', '=', 'Pallet.objectID')
                ->where('gc.objectID',$filter['container.child']);
        }
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
     * Implement paginate($filter)
     */
    public function paginate($filter) {
        return $this->rawFilter($filter)->paginate(10);
	}

    /**
     * @param $filter - may ask "what pallet is in this locationID?"
     * @return mixed - Pallet
     */
    public function findOrCreate($filter) {
        $pallet = $this->rawFilter($filter)->first();
        Log::debug('pallet: '.(isset($pallet) ? $pallet->Pallet_ID : "null"));
        // if we didn't find one, do we want to create one?
        if(!isset($pallet)) {
            // did they ask "what pallet is in this locationID?"
            if(isset($filter['container.parent']) && strlen($filter['container.parent']) > 3) {
                $params = $filter;
                if(!isset($params['x'])) $params['x'] = 100;
                if(!isset($params['y'])) $params['y'] = 100;
                if(!isset($params['z'])) $params['z'] = 100;
                if(!isset($params['Status'])) $params['Status'] = Config::get('constants.pallet.status.lock');
                Log::info('Create Location',$params);
                $pallet = $this->create($params);
                $this->locationRepository->putPalletIntoLocation($pallet->objectID, $filter['container.parent']);
            }
        }
        return $pallet;
    }

    /**
	 * Implement create($input)
	 */
	public function create($input) {
        Log::info('Create Pallet', $input);
		return Pallet::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $pallet = Pallet::find($id);

        //dd(__METHOD__.'('.__LINE__.')',compact('id','input','pallet'));
        Log::info("Update Pallet $id", $input);
        return $pallet->update($input);
    }

    /**
     * Implement delete($id)
     */
    public function delete($id) {
        $deleted = true;
        $pallet = $this->find($id);

        if(isset($pallet)) {
            //dd(__METHOD__.'('.__LINE__.')',compact('id','pallet'));
            Log::info("Delete Pallet $id");
            $deleted = $pallet->delete();

            // delete the container object also
            DB::connection(Pallet::CONNECTION_NAME)
                ->statement('delete from container where objectID = '.$id);
        }

        return $deleted;
    }

    /**
     * IMPORTANT: Call this function name on the Controller to verify this action is allowed
     * Implement putToteIntoPallet($toteID, $palletID)
     */
    public function putToteIntoPallet($toteID, $palletID) {
        $tote = GenericContainer::findOrFail($toteID);
        $pallet = Pallet::findOrFail($palletID);
        $container = DB::connection(Pallet::CONNECTION_NAME)
            ->table('container')
            ->where('objectID', $toteID)->first();

        Log::info("Put Tote $toteID into Pallet $palletID");
        if(isset($container)) {
            $result = DB::connection(Pallet::CONNECTION_NAME)
                ->table('container')
                ->where('containerID', $container->containerID)
                ->update(['parentID' => $palletID, 'objectID' => $toteID]);
            // $result === 1/true if the container was updated
            // $result === 0/false if no containers were updated
            if($result === 1 or $result === 0) return true;
        } else {
            $result = Container::create(['parentID' => $palletID, 'objectID' => $toteID]);
            // $result == container object created
            if(isset($result) and get_class($result) == 'App\vital3\Container') return true;
        }
        Log::error('putToteIntoPallet failed');
        //dd(__METHOD__.'('.__LINE__.')',compact('toteID','palletID','tote','pallet','container','result'));
        return ['putToteIntoPallet failed'];
    }

}
