<?php namespace vital40\Repositories;



interface ArticleRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($filter, $limit=10);

    public function paginate($filter);

	/**
	 * Get the Articles of this UPC ID
	 * @param $upcID
	 * @return mixed
	 */
	public function getUPCArticles($upcID, $limit=10);

    public function getPODArticles($filter, $limit=10);

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
