<?php namespace vital40\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use vital40\Role;
use vital40\RoleUser;
use \Auth;
use \Entrust;
use \Lang;
use \Request;

class DBRoleRepository implements RoleRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
        return $this->restrict(Role::get(), $limit);
	}

	/**
	 * Restrict the results to users Entrust::hasRole(..)
	 */
	protected function restrict($roles, $limit) {
        $results = [];
        $limit > 0 ? $i = 0 : $i = -1;
        foreach($roles as $role) {
            if(Entrust::hasRole($role['name']) && $i < $limit) {
                $results[] = $role;
                if($limit > 0) $i++;
            }
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('limit','roles','results','i'));
        return $results;
	}

	/**
	 * Implement lists($limit=10)
	 */
	public function lists($limit=10) {
        return $this->restrict(Role::orderBy('display_name', 'asc')->get(), $limit);
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
        $role = Role::findOrFail($id);
        if(Entrust::hasRole($role['name']) == false) {
            $message = Lang::get('internal.errors.notAuthorized', ['object' => 'Role', 'action' => 'find', 'id' => $id]);
            abort(401, $message, ['method' => __METHOD__, 'line' => __LINE__]);
        }
		// using the Eloquent model
		return $role;
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
		$query = Role::orderBy('name');
		if(isset($input['id']) && strlen($input['id']) > 2) {
			$query->where('id', '=', $input['id']);
		}
		if(isset($input['name']) && strlen($input['name']) > 2) {
			$query->where('name', 'like', $input['name'] . '%');
		}
		if(isset($input['display_name']) && strlen($input['display_name']) > 3) {
			$query->where('display_name', 'like', $input['display_name'] . '%');
		}
		if(isset($input['description']) && strlen($input['description']) > 3) {
			$query->where('description', 'like', $input['description'] . '%');
		}
        //dd(__METHOD__.'('.__LINE__.')',compact('input','query'));
        return $query;
	}

    /**
	 * Implement filterOn($input, $limit=10)
	 */
	public function filterOn($input, $limit=10) {
        return $this->restrict($this->rawFilter($input)->get(), $limit);
	}

    /**
	 * Implement paginate($input)
	 */
	public function paginate($input) {
        $roles = $this->restrict($this->rawFilter($input)->get(), 0);

        $perPage = 10;
        if(Request::has('page')) {
            $bypass = $perPage * (Request::get('page') - 1) * -1;
        } else {
            $bypass = 0;
        }
        $thisPage=[];
        foreach($roles as $value) {
            $bypass++;
            if($bypass > 0) {
                $thisPage[] = $value;
            }
            if(count($thisPage) == $perPage) break;
        }

        //dd(__METHOD__.'('.__LINE__.')',compact('input','roles','perPage','thisPage'));
        //return $this->rawFilter($input)->paginate(10);
        return new LengthAwarePaginator($thisPage, count($roles), $perPage, Request::get('page'), ['path' => Request::url()]);

    }

    /**
	 * Implement create($input)
	 */
	public function create($input) {
        $role = Role::create($input);
        // if you create it, you need access to it.
        $roleUser = RoleUser::create(['role_id' => $role['id'], 'user_id' => Auth::user()->id]);
        //dd(__METHOD__.'('.__LINE__.')',compact('input','role','roleUser'));
        return $role;
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$role = Role::find($id);
        if(Entrust::hasRole($role['name']) == false) {
            $message = \Lang::get('internal.errors.notAuthorized', ['object' => 'Role', 'action' => 'update', 'id' => $id]);
            abort(401, $message, ['method' => __METHOD__, 'line' => __LINE__]);
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('id','input','role'));
		return $role->update($input);
	}

}
