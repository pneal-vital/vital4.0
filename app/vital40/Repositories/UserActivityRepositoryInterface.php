<?php namespace vital40\Repositories;



interface UserActivityRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($input, $limit=10);

	public function paginate($input);

	/**
	 * Get the UserActivities for this $id and $classID
	 * @param $id - objectID to find
	 * @param $classID - Class name to find
	 * @return mixed array of UserActivity objects
     */
	public function getUserActivities($id, $classID);

	/**
	 * We may pass an $input array directly into our create method so it will Mass Assign.
	 * remember that we set the $fillable array, only those fields will get Mass Assigned.
	 * also we may set default values to $guarded fields.
	 */
	public function create($input);

	/**
	 * We may pass an $input array directly into our create method so it will Mass Assign.
	 * remember that we set the $fillable array, only those fields will get Mass Assigned.
	 * also we may set default values to $guarded fields.
	 */
	public function update($id, $input);

	/**
	 * Associate /Auth::user() with this $id and Purpose
	 */
	public function associate($id, $classID, $purpose);

    /**
     * Dissociate $name from receiving objects. At end of shift.
     * @param $name defaults to Auth::user()->name
     */
    public function dissociate($name='');

}
