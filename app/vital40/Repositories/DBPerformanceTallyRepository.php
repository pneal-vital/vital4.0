<?php namespace vital40\Repositories;

use App\vital40\PerformanceTally;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use \Auth;
use \Config;
use \Log;

class DBPerformanceTallyRepository implements PerformanceTallyRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return PerformanceTally::get();
        } elseif($limit == 1) {
            return PerformanceTally::first();
        }
		return PerformanceTally::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return PerformanceTally::findOrFail($id);
	}

	public function rawFilter($filter) {
		// Build a query based on filter $filter
		$query = PerformanceTally::orderBy('dateStamp', 'asc');
		if(isset($filter['dateStamp']) && strlen($filter['dateStamp']) > 4) {
			$query = $query->where('dateStamp', 'like', $filter['dateStamp'] . '%');
		}
		if(isset($filter['userName']) && strlen($filter['userName']) > 1) {
			$query = $query->where('userName', 'like', $filter['userName'] . '%');
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

    public function rawSum($filter) {
        //dd(__METHOD__."(".__LINE__.")",compact('filter'));
        /*
         * Note: Group by should really be groupBy(['year(dateStamp)', 'month(dateStamp)', 'day(dateStamp)', 'hour(dateStamp)'])
         * but eloquent places backticks (`) around the field names, and MYSQL does not like
         * group by `year(dateStamp)`, `month(dateStamp)`, `day(dateStamp)`, `hour(dateStamp)`;
         * as it thinks that `year(dateStamp)` refers to a field name, not a date function on field dateStamp.
         *
         * Therefor we are trying to get away with group by dateStamp. Hoping that the populating functions do not add minutes, seconds into the dateStamp.
         */
        if(isset($filter['groupBy']) and $filter['groupBy'] == 'dateStamp') {
            $query = PerformanceTally::groupBy(DB::raw('year(dateStamp), month(dateStamp), day(dateStamp), hour(dateStamp)'))->orderBy('dateStamp', 'asc')
                ->selectRaw('date_format(dateStamp,"%Y-%m-%d %H:%i") as dateStamp, sum(receivedUnits) as receivedUnits, sum(putAwayRec) as putAwayRec, sum(putAwayRplComb) as putAwayRplComb, sum(putAwayRplSngl) as putAwayRplSngl, sum(putAwayReserve) as putAwayReserve, sum(replenTotes) as replenTotes');
        } elseif(isset($filter['groupBy']) and $filter['groupBy'] == 'userName') {
            $query = PerformanceTally::groupBy('userName')->orderBy('userName', 'asc')
                ->selectRaw('userName, sum(receivedUnits) as receivedUnits, sum(putAwayRec) as putAwayRec, sum(putAwayRplComb) as putAwayRplComb, sum(putAwayRplSngl) as putAwayRplSngl, sum(putAwayReserve) as putAwayReserve, sum(replenTotes) as replenTotes');
        } else {
            $query = PerformanceTally::selectRaw('sum(receivedUnits) as receivedUnits, sum(putAwayRec) as putAwayRec, sum(putAwayRplComb) as putAwayRplComb, sum(putAwayRplSngl) as putAwayRplSngl, sum(putAwayReserve) as putAwayReserve, sum(replenTotes) as replenTotes');
        }

        if(isset($filter['fromDate']) && strlen($filter['fromDate']) > 4) {
            $query = $query->where('dateStamp', '>=', $filter['fromDate']);
        }
        if(isset($filter['toDate']) && strlen($filter['toDate']) > 4) {
            $query = $query->where('dateStamp', '<=', $filter['toDate']);
        }
        if(isset($filter['userName']) && strlen($filter['userName']) > 2) {
            $query = $query->where('userName', $filter['userName']);
        }
        return $query;
	}

    /**
     * Implement sumOn($filter)
     */
    public function sumOn($filter, $limit=10) {
        if($limit == 0) {
            return $this->rawSum($filter)->get();
        } elseif($limit == 1) {
            return $this->rawSum($filter)->first();
        }
        return $this->rawSum($filter)->limit($limit)->get();
    }

    /**
     * Implement paginate($filter)
     */
    public function paginate($filter) {
        return $this->rawFilter($filter)->paginate(10);
	}

    /**
     * Implement paginateSum($filter)
     */
    public function paginateSum($filter) {
        //$result = $this->rawSum($filter)->get();
        //dd(__METHOD__."(".__LINE__.")",compact('filter','result'));
        return $this->rawSum($filter)->paginate(10);
	}

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return PerformanceTally::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $performanceTally = PerformanceTally::findOrFail($id);

        //dd($input);
        return $performanceTally->update($input);
    }

    /**
     * Implement increment($input)
     */
    public function increment($input) {
        $userName = Auth::user()->name;
        $dateStamp = Carbon::now()->minute(00)->second(00);

        // find or create
        $performanceTally = PerformanceTally::where('userName', $userName)->where('dateStamp', $dateStamp)->first();
        if(isset($performanceTally) == false) {
            $performanceTally = $this->create([]);
        }
        Log::debug(__METHOD__."(".__LINE__."):  performanceTally class: ".(is_array($performanceTally) ? "array()" : get_class($performanceTally)));

        // increment each attribute in $input
        foreach($input as $key => $value) {
            $currentValue = $performanceTally->getAttribute($key);
            if(isset($currentValue)) {
                $performanceTally->setAttribute($key, $currentValue + $value);
            }
        }

        // update new values
        $performanceTally->update();
    }

}
