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

	protected function rawFilter($filter) {
		// Build a query based on filter $filter
		$query = Pallet::orderBy('Pallet_ID', 'asc');
		if(isset($filter['Pallet_ID']) && strlen($filter['Pallet_ID']) > 2) {
			$query = $query->where('Pallet_ID', 'like', ltrim($filter['Pallet_ID'],'0') . '%');
		}
		if(isset($filter['Pallet_ID.prefix']) && is_array($filter['Pallet_ID.prefix'])) {
			$query = $query->whereRaw("substring(Pallet_ID,1,3) in ('".implode("','", $filter['Pallet_ID.prefix'])."')");
		}
        if(isset($filter['Status']) && is_array($filter['Status'])) {
            $query = $query->whereRaw("Status in ('".implode("','", $filter['Status'])."')");
        }
        elseif(isset($filter['Status']) && strlen($filter['Status']) > 3) {
            $query = $query->where('Status', '=', $filter['Status']);
        }
        //TODO these next filters should add to $query, not replace it, see: ArticleFlow.closeTote(..)
        if(isset($filter['container.parent']) && strlen($filter['container.parent']) > 3) {
            $query = $this->parentSelect($filter['container.parent']);
        }
        if(isset($filter['container.child']) && strlen($filter['container.child']) > 3) {
            $query = $this->childSelect($filter['container.child']);
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
        return $this->rawFilter($filter)->paginate();
	}

    /**
     * @param $filter - may ask "what pallet is in ths locationID?"
     * @return mixed - Pallet
     */
    public function findOrCreate($filter) {
        $pallet = $this->rawFilter($filter)->first();
        Log::debug(__METHOD__."(".__LINE__."): pallet: ".(isset($pallet) ? $pallet->Pallet_ID : "null"));
        // if we didn't find one, do we want to create one?
        if(!isset($pallet)) {
            // did they ask "what pallet is in this locationID?"
            if(isset($filter['container.parent']) && strlen($filter['container.parent']) > 3) {
                $params = $filter;
                if(!isset($params['x'])) $params['x'] = 100;
                if(!isset($params['y'])) $params['y'] = 100;
                if(!isset($params['z'])) $params['z'] = 100;
                if(!isset($params['Status'])) $params['Status'] = Config::get('constants.pallet.status.lock');
                Log::debug(__METHOD__."(".__LINE__."): create params");
                Log::debug($params);
                $pallet = $this->create($params);
                Log::debug(__METHOD__."(".__LINE__."): create Pallet");
                Log::debug($pallet);
                $this->locationRepository->putPalletIntoLocation($pallet->objectID, $filter['container.parent']);
            }
        }
        return $pallet;
    }

    /**
     * Given a parent objectID, build a query to find Pallet data through hierarchy chain.
     *
     * @return mixed
     */
    private function parentSelect($id) {
        // Using QueryBuilder joins
        return DB::connection('vitaldev')
            ->table('Pallet')
            ->join('container', 'container.objectID', '=', 'Pallet.objectID')
            ->where('container.parentID', '=', $id);
    }

    /**
     * Given a child objectID, build a query to find Pallet data through hierarchy chain.
     *
     * @return mixed
     */
    private function childSelect($id) {
        // Using QueryBuilder joins
        return DB::connection('vitaldev')
            ->table('container')
            ->join('Pallet', 'Pallet.objectID', '=', 'container.parentID')
            ->select('Pallet.objectID', 'Pallet.Pallet_ID')
            ->where('container.objectID', '=', $id);
    }

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return Pallet::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $pallet = Pallet::find($id);

        //dd($input);
        return $pallet->update($input);
    }

    /**
     * Implement putToteIntoPallet($toteID, $palletID)
     */
    public function putToteIntoPallet($toteID, $palletID) {
        $tote = GenericContainer::findOrFail($toteID);
        $pallet = Pallet::findOrFail($palletID);
        $container = DB::connection('vitaldev')
            ->table('container')
            ->where('objectID', $toteID)->first();

        if(isset($container)) {
            DB::connection('vitaldev')
                ->table('container')
                ->where('containerID', $container->containerID)
                ->update(['parentID' => $palletID, 'objectID' => $toteID]);
        } else {
            Container::create(['parentID' => $palletID, 'objectID' => $toteID]);
        }
    }

}
