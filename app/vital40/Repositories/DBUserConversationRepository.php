<?php namespace vital40\Repositories;

use vital40\UserConversation;
use \Log;

class DBUserConversationRepository implements UserConversationRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return UserConversation::get();
        } elseif($limit == 1) {
            return UserConversation::first();
        }
		return UserConversation::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return UserConversation::findOrFail($id);
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
		$query = UserConversation::orderBy('activityID', 'desc');
		if(isset($input['POD']) && strlen($input['POD']) > 3) {
			$query = $query->where('POD', 'like', $input['POD'] . '%');
		}
		if(isset($input['Article']) && strlen($input['Article']) > 3) {
			$query = $query->where('Article', 'like', $input['Article'] . '%');
		}
		if(isset($input['User_Name']) && strlen($input['User_Name']) > 3) {
			$query = $query->where('User_Name', 'like', $input['User_Name'] . '%');
		}
		if(isset($input['Sender_Name']) && strlen($input['Sender_Name']) > 3) {
			$query = $query->where('Sender_Name', 'like', $input['Sender_Name'] . '%');
		}
		if(isset($input['created_at']) && strlen($input['created_at']) > 6) {
			$query = $query->where('created_at', 'like', $input['created_at'] . '%');
		}
		if(isset($input['updated_at']) && strlen($input['updated_at']) > 6) {
			$query = $query->where('updated_at', 'like', $input['updated_at'] . '%');
		}
		if(isset($input['Text']) && strlen($input['Text']) > 3) {
			$query = $query->where('Text', 'like', $input['Text'] . '%');
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
	 * Implement create($input)
	 */
	public function create($input) {
        Log::debug('input:',$input);
		return UserConversation::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$userConversation = UserConversation::find($id);

		//dd($input);
		return $userConversation->update($input);
	}

}
