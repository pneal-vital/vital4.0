<?php namespace vital40\Repositories;



interface ToteRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($filter, $limit=10);

	public function paginate($filter);

    /**
     * @param $filter - because they provide a new Carton_ID to this method
     * @return mixed - Tote
     */
    public function findOrCreate($filter);

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

	public function delete($id);

	public function putInventoryIntoTote($inventoryID, $toteID);

    public function openToteContents($locationID, $podID);

    public function isEmpty($toteID);

}
