<?php namespace vital3\Repositories;



interface PalletRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($input, $limit=10);

	public function paginate($input);

    /**
     * @param $filter - may ask "what pallet is in ths locationID?"
     * @return mixed - Pallet
     */
    public function findOrCreate($filter);

    /**
	 * We may pass an $input array directly into our create method so it will Mass Assign.
	 * Remember that we set the $fillable array, only those fields will get Mass Assigned.
	 * Also we may set default values to $guarded fields.
	 */
	public function create($input);

    /**
     * We may pass an $input array directly into our update method so it will Mass Assign.
     * Remember that we set the $fillable array, only those fields will get Mass Assigned.
     * Also we may set default values to $guarded fields.
     */
    public function update($id, $input);

	public function delete($id);

	public function putToteIntoPallet($toteID, $palletID);
}
