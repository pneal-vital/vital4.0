<?php namespace vital40\Repositories;

use vital40\SessionType;
use \Request;

class DBSessionTypeRepository implements SessionTypeRepositoryInterface {

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		$sid = Request::session()->getId();
		// using the Eloquent model
		return SessionType::find($sid.'-'.$id);
	}

    public function get($id, $default) {
        $found = $this->find($id);
        if(isset($found) && isset($found->payload)) {
            return $found->payload;
        }
        return $default;
    }

    /**
	 * Implement create($input)
	 */
	public function create($id, $input) {
        $sid = Request::session()->getId();
        $payload = serialize($input);
		return SessionType::create(['id' => $sid.'-'.$id, 'payload' => $payload, 'last_activity' => Request::session()->getMetadataBag()->getLastUsed()]);
	}

    public function put($id, $input) {
        $found = $this->find($id);
        if(isset($found)) {
            return $found->update(['payload' => $input, 'last_activity' => Request::session()->getMetadataBag()->getLastUsed()]);
        }

        $sid = Request::session()->getId();
        return SessionType::create(['id' => $sid.'-'.$id, 'payload' => $input, 'last_activity' => Request::session()->getMetadataBag()->getLastUsed()]);
    }

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
        $sid = Request::session()->getId();
		$sessionType = SessionType::findOrFail($sid.'-'.$id);

        $payload = serialize($input);
		return $sessionType->update(['payload' => $payload, 'last_activity' => Request::session()->getMetadataBag()->getLastUsed()]);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function delete($id) {
        $found = $this->find($id);
        if(isset($found)) {
            return $found->delete();
        }
        return false;
	}

}
