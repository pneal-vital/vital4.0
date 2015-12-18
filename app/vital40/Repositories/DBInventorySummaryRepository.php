<?php namespace vital40\Repositories;

use App\vital40\InventorySummary;
use Illuminate\Support\Facades\DB;
use \Auth;
use \Config;
use \Log;

class DBInventorySummaryRepository implements InventorySummaryRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return InventorySummary::get();
        } elseif($limit == 1) {
            return InventorySummary::first();
        }
		return InventorySummary::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return InventorySummary::findOrFail($id);
	}

	public function rawFilter($filter) {
		// Build a query based on filter $filter
		$query = InventorySummary::orderBy('Client_SKU', 'asc');
		if(isset($filter['objectID']) && strlen($filter['objectID']) > 3) {
			$query = $query->where('objectID', 'like', $filter['objectID'] . '%');
		}
		if(isset($filter['Client_SKU']) && strlen($filter['Client_SKU']) > 3) {
			$query = $query->where('Client_SKU', 'like', $filter['Client_SKU'] . '%');
		}
		if(isset($filter['Description']) && strlen($filter['Description']) > 3) {
			$query = $query->where('Description', 'like', $filter['Description'] . '%');
		}

        /*
         * Pick face quantity choices
         */
        if(isset($filter['pickQty_rb'])) {
            if($filter['pickQty_rb'] == 'zero') {
                $query = $query->where('pickQty', '=', '0');
            } elseif($filter['pickQty_rb'] == 'belowMin') {
                $query = $query->where('pickQty', '<', '3');
            } elseif($filter['pickQty_rb'] == 'aboveMin') {
                $query = $query->where('pickQty', '>', '2');
            }
        }

        /*
         * Activity location quantity choices
         */
        if(isset($filter['actQty_rb'])) {
            if($filter['actQty_rb'] == 'zero') {
                $query = $query->where('actQty', '=', '0');
            } elseif($filter['actQty_rb'] == 'aboveZero') {
                $query = $query->where('actQty', '>', '0');
            }
        }

        /*
         * Reserve quantity choices
         */
        if(isset($filter['resQty_rb'])) {
            if($filter['resQty_rb'] == 'zero') {
                $query = $query->where('resQty', '=', '0');
            } elseif($filter['resQty_rb'] == 'aboveZero') {
                $query = $query->where('resQty', '>', '0');
            }
        }

        /*
         * Replen Priority choices
         */
        if(isset($filter['replenPrty_cb_noReplen'])
        or isset($filter['replenPrty_cb_20orBelow'])
        or isset($filter['replenPrty_cb_40orBelow'])
        or isset($filter['replenPrty_cb_60orBelow'])) {
            $query->where(function ($query) use ($filter) {
                if (isset($filter['replenPrty_cb_noReplen']) && $filter['replenPrty_cb_noReplen'] == 'on') {
                    $query->orWhereNull('replenPrty')
                        ->orWhere('replenPrty', '=', '0');
                }
                if (isset($filter['replenPrty_cb_20orBelow']) && $filter['replenPrty_cb_20orBelow'] == 'on') {
                    $query->orWhereBetween('replenPrty', ['1', '20']);
                }
                if (isset($filter['replenPrty_cb_40orBelow']) && $filter['replenPrty_cb_40orBelow'] == 'on') {
                    $query->orWhereBetween('replenPrty', ['21', '40']);
                }
                if (isset($filter['replenPrty_cb_60orBelow']) && $filter['replenPrty_cb_60orBelow'] == 'on') {
                    $query->orWhereBetween('replenPrty', ['41', '60']);
                }
            });
        }
        //dd(__METHOD__."(".__LINE__.")", compact('filter', 'query'));

		if(isset($filter['created_at']) && strlen($filter['created_at']) > 1) {
			$query = $query->where('created_at', 'like', $filter['created_at'] . '%');
		}
		if(isset($filter['updated_at']) && strlen($filter['updated_at']) > 1) {
			$query = $query->where('updated_at', 'like', $filter['updated_at'] . '%');
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
            $query = InventorySummary::groupBy(DB::raw('year(dateStamp), month(dateStamp), day(dateStamp), hour(dateStamp)'))->orderBy('dateStamp', 'asc')
                ->selectRaw('date_format(dateStamp,"%Y-%m-%d %H:%i") as dateStamp, sum(receivedUnits) as receivedUnits, sum(putAwayRec) as putAwayRec, sum(putAwayRplComb) as putAwayRplComb, sum(putAwayRplSngl) as putAwayRplSngl, sum(putAwayReserve) as putAwayReserve, sum(replenTotes) as replenTotes');
        } elseif(isset($filter['groupBy']) and $filter['groupBy'] == 'userName') {
            $query = InventorySummary::groupBy('userName')->orderBy('userName', 'asc')
                ->selectRaw('userName, sum(receivedUnits) as receivedUnits, sum(putAwayRec) as putAwayRec, sum(putAwayRplComb) as putAwayRplComb, sum(putAwayRplSngl) as putAwayRplSngl, sum(putAwayReserve) as putAwayReserve, sum(replenTotes) as replenTotes');
        } else {
            $query = InventorySummary::selectRaw('sum(receivedUnits) as receivedUnits, sum(putAwayRec) as putAwayRec, sum(putAwayRplComb) as putAwayRplComb, sum(putAwayRplSngl) as putAwayRplSngl, sum(putAwayReserve) as putAwayReserve, sum(replenTotes) as replenTotes');
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
		return InventorySummary::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $inventorySummary = InventorySummary::findOrFail($id);

        //dd($input);
        return $inventorySummary->update($input);
    }

    /**
     * Implement increment($input)
     */
    public function fireStoredProcedure() {
        Log::debug(__METHOD__."(".__LINE__."):  about to fire populate_InventorySummary");

//        this would work great if the stored procedure returned a value
//        DB::connection('vitaldev')
//            ->select('call populate_InventorySummary()');

//      works, because this stored procedure does not return anything
        DB::connection('vitaldev')
            ->statement(DB::raw('call populate_InventorySummary()'));

        Log::debug(__METHOD__."(".__LINE__."):  returned from populate_InventorySummary");
    }

}
