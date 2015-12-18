<?php namespace vital40\Repositories;

use vital40\Role;

class DBRoleRepository implements RoleRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return Role::get();
        } elseif($limit == 1) {
            return Role::first();
        }
        return Role::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return Role::findOrFail($id);
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
        //dd($input);
		$query = Role::orderBy('name');
		if(isset($input['id']) && strlen($input['id']) > 2) {
			$query = $query->where('id', '=', $input['id']);
		}
		if(isset($input['name']) && strlen($input['name']) > 2) {
			$query = $query->where('name', 'like', $input['name'] . '%');
		}
		if(isset($input['display_name']) && strlen($input['display_name']) > 3) {
			$query = $query->where('display_name', 'like', $input['display_name'] . '%');
		}
		if(isset($input['description']) && strlen($input['description']) > 3) {
			$query = $query->where('description', 'like', $input['description'] . '%');
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
		return Role::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$role = Role::find($id);

		//dd($input);
		return $role->update($input);
	}

}
