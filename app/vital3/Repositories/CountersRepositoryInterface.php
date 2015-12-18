<?php namespace vital3\Repositories;


interface CountersRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($input, $limit=10);

	public function create($input);

    public function increment($id);

}
