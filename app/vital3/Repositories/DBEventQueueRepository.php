<?php namespace vital3\Repositories;

use App\vital3\EventQueue;

class DBEventQueueRepository implements EventQueueRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return EventQueue::get();
        } elseif($limit == 1) {
            return EventQueue::first();
        }
		return EventQueue::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return EventQueue::findOrFail($id);
	}

	/**
	 * Implement filterOn($filter)
	 */
	public function filterOn($filter, $limit=10) {
		// Build a query based on filter $filter
		$query = EventQueue::orderBy('eventID', 'asc');
		if(isset($filter['eventID']) && strlen($filter['eventID']) > 3) {
			$query = $query->where('eventID', 'like', $filter['eventID'] . '%');
		}
        if(isset($filter['parameters']) && strlen($filter['parameters']) > 3) {
            $query = $query->where('parameters', 'like', '%' . $filter['parameters'] . '%');
        }
        if(isset($filter['priority']) && strlen($filter['priority']) > 0) {
            $query = $query->where('priority', $filter['priority']);
        }
        if($limit == 0) {
            return $query->get();
        } elseif($limit == 1) {
            return $query->first();
        }
		return $query->limit($limit)->get();
	}

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return EventQueue::create($input);
	}

    /**
     * Implement update($id, $input)
     */
    public function update($id, $input) {
        $eventQueue = EventQueue::find($id);

        //dd($input);
        return $eventQueue->update($input);
    }

}
