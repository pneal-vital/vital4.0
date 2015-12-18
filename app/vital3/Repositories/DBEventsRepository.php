<?php namespace vital3\Repositories;

use App\vital3\Container;
use App\vital3\GenericContainer;
use App\vital3\Events;
use Illuminate\Support\Facades\DB;

class DBEventsRepository implements EventsRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return Events::get();
        } elseif($limit == 1) {
            return Events::first();
        }
		return Events::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return Events::findOrFail($id);
	}

	/**
	 * Implement filterOn($filter)
	 */
	public function filterOn($filter, $limit=10) {
		// Build a query based on filter $filter
		$query = Events::orderBy('eventID', 'asc');
		if(isset($filter['eventID']) && strlen($filter['eventID']) > 0) {
			$query = $query->where('eventID', $filter['eventID']);
		}
        if(isset($filter['step']) && strlen($filter['step']) > 0) {
            $query = $query->where('step', $filter['step']);
        }
        if(isset($filter['plugIn']) && strlen($filter['plugIn']) > 3) {
            $query = $query->where('plugIn', 'like', $filter['plugIn'] . '%');
        }
        if(isset($filter['baseParameters']) && strlen($filter['baseParameters']) > 3) {
            $query = $query->where('baseParameters', 'like', '%' . $filter['baseParameters'] . '%');
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
		return Events::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $event = Events::find($id);

        //dd($input);
        return $event->update($input);
    }

}
