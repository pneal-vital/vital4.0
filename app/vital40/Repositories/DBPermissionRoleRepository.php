<?php namespace vital40\Repositories;

use vital40\PermissionRole;

class DBPermissionRoleRepository implements PermissionRoleRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return PermissionRole::get();
        } elseif($limit == 1) {
            return PermissionRole::first();
        }
        return PermissionRole::limit($limit)->get();
	}

	/**
	 * Implement find($id)
     *
     * Note: The primary key of PermissionRole is defined as ['permission_id','role_id']
     * therefore $id must be an array ['permission_id' => value, 'role_id' => value]
	 */
	public function find($id) {
		// using the Eloquent model
		return PermissionRole::findOrFail($id);
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
		$query = PermissionRole::orderBy('role_id')->orderBy('permission_id');
		if(isset($input['permission_id']) && strlen($input['permission_id']) > 0 && $input['permission_id'] > 0) {
			$query = $query->where('permission_id', '=', $input['permission_id']);
		}
		if(isset($input['role_id']) && strlen($input['role_id']) > 0 && $input['role_id'] > 0) {
			$query = $query->where('role_id', '=', $input['role_id']);
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
		return PermissionRole::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$role = PermissionRole::find($id);

		//dd(__METHOD__.'('.__LINE__.')'.': ',compact('id','input','role'));
		return $role->update($input);
	}

}
