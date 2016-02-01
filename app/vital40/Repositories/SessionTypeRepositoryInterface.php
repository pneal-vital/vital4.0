<?php namespace vital40\Repositories;


interface SessionTypeRepositoryInterface {

	public function find($id);

	/**
	 * Return the session payload
	 * @param $id
	 * @param $default
	 * @return mixed
	 */
	public function get($id, $default);

	/**
	 * We may pass an $input array directly into our create method so it will Mass Assign.
	 * Remember that we set the $fillable array, only those fields will get Mass Assigned.
	 * Also we may set default values to $guarded fields.
	 */
	public function create($id, $input);

	/**
	 * Return the session payload
	 * @param $id
	 * @param $default
	 * @return mixed
	 */
	public function put($id, $default);

	/**
	 * We may pass an $input array directly into our create method so it will Mass Assign.
	 * Remember that we set the $fillable array, only those fields will get Mass Assigned.
	 * Also we may set default values to $guarded fields.
	 */
	public function update($id, $input);

	public function delete($id);

}
