<?php namespace vital40\Repositories;


interface InventorySummaryRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($filter, $limit=10);

	public function sumOn($filter, $limit=10);

	public function paginate($filter);

	public function paginateSum($filter);

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

    public function fireStoredProcedure();

}
