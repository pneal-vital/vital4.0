<?php namespace vital40\Repositories;


interface JobExperienceRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($input, $limit=10);

    /**
     * Calculate the average count and elapsed time for entries of $filter
     * @param $filter
     * @return mixed
     */
    public function averageCountTime($filter);

	/**
	 * Calculate an estimated elapsed time (minutes) for itemCount entries of $filter
	 * @param $filter
	 * @return mixed
	 */
	public function elapsedTime($itemCount, $filter);

	/**
	 * We may pass an $input array directly into our create method so it will Mass Assign.
	 * Remember that we set the $fillable array, only those fields will get Mass Assigned.
	 * Also we may set default values to $guarded fields.
	 */
	public function create($input);

}
