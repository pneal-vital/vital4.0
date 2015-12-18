<?php namespace vital3\Repositories;



interface InboundOrderRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($input, $limit=10);

	public function paginate($input);

	/**
	 * We may pass an $input array directly into our create method so it will Mass Assign.
	 * remember that we set the $fillable array, only those fields will get Mass Assigned.
	 * also we may set default values to $guarded fields.
	 */
	public function create($input);

	// functions related to Inbound_Order_Additional
	public function getAdditional($id);

}
