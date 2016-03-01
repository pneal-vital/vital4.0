<?php namespace vital40\Repositories;

use vital40\RoleUser;

class DBRoleUserRepository implements RoleUserRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return RoleUser::get();
        } elseif($limit == 1) {
            return RoleUser::first();
        }
        return RoleUser::limit($limit)->get();
	}

	/**
	 * Implement find($id)
     *
     * Note: The primary key of RoleUser is defined as ['user_id','role_id']
     * therefore $id must be an array ['user_id' => value, 'role_id' => value]
	 */
	public function find($id) {
		// using the Eloquent model
		return RoleUser::findOrFail($id);
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
		$query = RoleUser::orderBy('user_id')->orderBy('role_id');
		if(isset($input['user_id']) && strlen($input['user_id']) > 0 && $input['user_id'] > 0) {
			$query->where('user_id', '=', $input['user_id']);
		}
		if(isset($input['name']) && strlen($input['name']) > 3) {
			$query->join('users', 'users.id', '=', 'role_user.user_id')
				  ->where('users.name', 'like', $input['name'].'%');
		}
		if(isset($input['role_id']) && strlen($input['role_id']) > 0 && $input['role_id'] > 0) {
			$query->where('role_id', '=', $input['role_id']);
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
	 * Implement paginate($input)
	 */
	public function paginate($input) {
        return $this->rawFilter($input)->paginate(10);
	}

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return RoleUser::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$role = RoleUser::find($id);

		//dd(__METHOD__.'('.__LINE__.')'.': ',compact('id','input','role'));
		return $role->update($input);
	}

}
