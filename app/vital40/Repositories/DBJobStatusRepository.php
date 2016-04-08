<?php namespace vital40\Repositories;

use App\vital40\JobStatus;

class DBJobStatusRepository implements JobStatusRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return JobStatus::get();
        } elseif($limit == 1) {
            return JobStatus::first();
        }
        return JobStatus::limit($limit)->get();
	}

	/**
	 * Implement find($id)
     *
     * Note: The primary key of JobStatus is defined as ['name','id']
     * therefore $id must be an array ['name' => value, 'id' => value]
	 */
	public function find($id) {
		// using the Eloquent model
		return JobStatus::where('name', '=', $id['name'])->where('id', '=', $id['id'])->first();
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
		$query = JobStatus::query();
		if(isset($input['name']) && strlen($input['name']) > 0 && $input['name'] > 0) {
			$query->where('name', '=', $input['name']);
		}
		if(isset($input['id']) && strlen($input['id']) > 0 && $input['id'] > 0) {
			$query->where('id', '=', $input['id']);
		}
		if(isset($input['parameters']) && count($input['parameters']) > 0) {
			$query->where('parameters', '=', serialize($input['parameters']));
		}
		if(isset($input['mostRecent'])) {
            $query->where('requested', '<=', $input['mostRecent']);
			$query->orderBy('id', 'desc');
		} else {
			$query->orderBy('name')->orderBy('id');
		}
		//dd(__METHOD__.'('.__LINE__.')'.': ',compact('input','query'));
        return $query;
	}

    /**
	 * Implement filterOn($input, $limit=10)
	 */
	public function filterOn($input, $limit=10) {
        if($limit == 0) {
            return $this->rawFilter($input)->get();
        } elseif($limit == 1) {
            return $this->rawFilter($input)->first();
        }
		return $this->rawFilter($input)->limit($limit)->get();
	}

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return JobStatus::create($input);
	}

    /**
	 * Implement update($input)
	 */
	public function update($id, $input) {
		$jobStatus = $this->find($id);
		return $jobStatus->update($input);
	}

}
