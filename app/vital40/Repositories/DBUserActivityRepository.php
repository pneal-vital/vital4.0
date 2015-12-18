<?php namespace vital40\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use vital40\UserActivity;
use \Auth;
use \Config;
use \Log;

class DBUserActivityRepository implements UserActivityRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return UserActivity::get();
        } elseif($limit == 0) {
            return UserActivity::first();
        }
		return UserActivity::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return UserActivity::findOrFail($id);
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
		$query = UserActivity::orderBy('User_Name', 'asc')->orderBy('created_at', 'desc');
		if(isset($input['id']) && strlen($input['id']) > 3) {
			$query = $query->where('id', 'like', $input['id'] . '%');
		}
		if(isset($input['classID']) && strlen($input['classID']) > 3) {
			$query = $query->where('classID', 'like', $input['classID'] . '%');
		}
		if(isset($input['User_Name']) && strlen($input['User_Name']) > 3) {
			$query = $query->where('User_Name', 'like', $input['User_Name'] . '%');
		}
		if(isset($input['created_at']) && strlen($input['created_at']) > 6) {
			$query = $query->where('created_at', 'like', $input['created_at'] . '%');
		}
		if(isset($input['updated_at']) && strlen($input['updated_at']) > 6) {
			$query = $query->where('updated_at', 'like', $input['updated_at'] . '%');
		}
		if(isset($input['Purpose']) && strlen($input['Purpose']) > 3) {
			$query = $query->where('Purpose', 'like', $input['Purpose'] . '%');
		}
        return $query;
    }

    /**
     * Implement filterOn($input, $limit)
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
     * Implement paginate($input)
     */
    public function paginate($input) {
        return $this->rawFilter($input)->paginate(10);
	}

	/**
	 * Get the UserActivities for this $id and $classID
	 * TODO consider making this into a scopeUserActivities($query, $ID, $classID)
	 * @param $id
	 * @return mixed
     */
	public function getUserActivities($id, $classID) {
		return UserActivity::where('id', $id)->where('classID', $classID)->get();
	}

	/**
	 * Implement create($input)
	 */
	public function create($input) {
		return UserActivity::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$userActivity = UserActivity::find($id);

		//dd($input);
		return $userActivity->update($input);
	}

	/**
	 * Associate /Auth::user() with this $id and Purpose
	 */
	public function associate($id, $classID, $purpose) {

		$input = ['id' => $id, 'classID' => $classID, 'User_Name' => Auth::user()->name, 'Purpose' => $purpose];
		//dd($input);
        Log::debug(__METHOD__."(".__LINE__."):  id: $id, classID: $classID, User_Name: ".Auth::user()->name.", Purpose: $purpose");

		// Are we already associated with this classID?
		$userActivity = UserActivity::where('id', $id)
			->where('classID', $classID)
			->where('User_Name', Auth::user()->name)
			->first();
		if(isset($userActivity)) {
			$userActivity->updated_at = Carbon::now();
			$userActivity->save();
			return $userActivity;
		}

		// We need to verify User_Name is not already associated with any other of this classID
        $activityIDs = DB::connection('vitaldev')
            ->table('User_Activity')
            ->select('activityID')
            ->where('User_Name', Auth::user()->name)
            ->where('classID', $classID)
            ->get();
        if(isset($activityIDs) && count($activityIDs)) {
            foreach($activityIDs as $activityID) {
                UserActivity::destroy($activityID->activityID);
            }
        }

		return UserActivity::create($input);
	}

	/**
	 * Dissociate /Auth::user() from receiving objects. At end of shift.
	 */
	public function dissociate($name='') {
        if(strlen($name) == 0) $name = Auth::user()->name;
        Log::debug(__METHOD__."(".__LINE__."):  User_Name: $name");

		// find what User_Name is not already associated with
        $activityIDs = DB::connection('vitaldev')
            ->table('User_Activity')
            ->select('activityID')
            ->where('User_Name', $name)
            ->get();
        if(isset($activityIDs) && count($activityIDs)) {
            foreach($activityIDs as $activityID) {
                UserActivity::destroy($activityID->activityID);
            }
        }
	}

}
