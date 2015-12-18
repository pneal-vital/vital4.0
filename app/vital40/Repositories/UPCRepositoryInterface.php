<?php namespace vital40\Repositories;


interface UPCRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($filter, $limit=10);

	public function paginate($filter);

	public function getArticleUPCs($articleID, $limit=10);

	public function paginateArticleUPCs($articleID);

    public function getToteUPCs($toteID, $limit=10);

    public function combine($filter);

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

}
