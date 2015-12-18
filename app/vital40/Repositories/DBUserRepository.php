<?php namespace vital40\Repositories;

use vital40\User;

class DBUserRepository implements UserRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return User::get();
        } elseif($limit == 1) {
            return User::first();
        }
        return User::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return User::findOrFail($id);
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
        //dd($input);
		$query = User::orderBy('name');
		if(isset($input['id']) && strlen($input['id']) > 2) {
			$query = $query->where('id', '=', $input['id']);
		}
		if(isset($input['name']) && strlen($input['name']) > 2) {
			$query = $query->where('name', 'like', $input['name'] . '%');
		}
		if(isset($input['email']) && strlen($input['email']) > 3) {
			$query = $query->where('email', 'like', $input['email'] . '%');
		}
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
	 * Implement paginate($input)
	 */
	public function paginate($input) {
        return $this->rawFilter($input)->paginate(10);
	}

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return User::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$user = User::find($id);

		//dd($input);
		return $user->update($input);
	}

}
