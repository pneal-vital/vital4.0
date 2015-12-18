<?php namespace vital3\Repositories;


interface EventsRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($input, $limit=10);

	public function create($input);

    public function update($id, $input);

}
