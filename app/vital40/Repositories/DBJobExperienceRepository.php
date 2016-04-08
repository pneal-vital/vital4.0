<?php namespace vital40\Repositories;

use App\vital40\JobExperience;
use Illuminate\Support\Facades\DB;

class DBJobExperienceRepository implements JobExperienceRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return JobExperience::get();
        } elseif($limit == 1) {
            return JobExperience::first();
        }
        return JobExperience::limit($limit)->get();
	}

	/**
	 * Implement find($id)
     *
     * Note: The primary key of JobExperience is defined as ['name','id']
     * therefore $id must be an array ['name' => value, 'id' => value]
	 */
	public function find($id) {
		// using the Eloquent model
		return JobExperience::findOrFail($id);
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
		$query = JobExperience::query();
		if(is_string($input['name']) && strlen($input['name']) > 0) {
			#$query->where('name', '=', str_replace('\\', '\\\\', $input['name']));
			$query->where('name', '=', $input['name']);
		}
		if(isset($input['id']) && strlen($input['id']) > 0 && $input['id'] > 0) {
			$query->where('id', '=', $input['id']);
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
     * Calculate the average count and elapsed time for entries of $filter
     * @param $filter
     * @return mixed
     */
    public function averageCountTime($filter) {
        /*
         * select avg(experience) as avgCount, avg(elapsed) as avgTime from job_experience where name = 'App\\Jobs\\ReworkReport';
        +----------+---------+
        | avgCount | avgTime |
        +----------+---------+
        | 254.0000 |  0.0000 |
        +----------+---------+
        1 row in set (0.00 sec)
         */
        return $this->rawFilter($filter)->select(DB::raw('avg(experience) as avgCount'), DB::raw('avg(elapsed) as avgTime'))->first();
    }

	/**
	 * Calculate an estimated elapsed time (minutes) for itemCount entries of $filter
	 * @param $filter
	 * @return mixed
	 */
	public function elapsedTime($itemCount, $filter) {
        $avgCountTime = $this->averageCountTime($filter);
        $avgCount = $avgCountTime->avgCount > 0 ? $avgCountTime->avgCount : 1000.000;
        $avgTime = $avgCountTime->avgTime > 0 ? $avgCountTime->avgTime : 1.000;
        $estimatedTime = intval(($itemCount * $avgTime / $avgCount) + .5);
		return $estimatedTime;
	}

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return JobExperience::create($input);
	}

}
