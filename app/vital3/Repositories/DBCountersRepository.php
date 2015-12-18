<?php namespace vital3\Repositories;

use App\vital3\Counters;

class DBCountersRepository implements CountersRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return Counters::get();
        } elseif($limit == 1) {
            return Counters::first();
        }
		return Counters::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return Counters::findOrFail($id);
	}

	/**
	 * Implement filterOn($filter)
	 */
	public function filterOn($filter, $limit=10) {
		// Build a query based on filter $filter
		$query = Counters::orderBy('Name', 'asc');
        if(isset($filter['Name']) && strlen($filter['Name']) > 3) {
            $query = $query->where('Name', 'like', $filter['Name'] . '%');
        }
        if($limit == 0) {
            return $query->get();
        } else if($limit == 1) {
            return $query->first();
        }
		return $query->limit($limit)->get();
	}

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return Counters::create($input);
	}

    /**
     * Implement increment($id)
     */
    public function increment($id) {
        $counter = Counters::find($id);
        if(isset($counter) == false) {
            $counter = $this->create(['Name' => $id]);
        }
        if(isset($counter) == true) {
            $counter->Value += 1;
            $counter->update();
        }

        //dd($input);
        return $counter->Value;
    }

}
