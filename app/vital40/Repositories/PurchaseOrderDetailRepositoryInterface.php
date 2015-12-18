<?php namespace vital40\Repositories;


interface PurchaseOrderDetailRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($filter, $limit=10);

	public function paginate($filter);

    public function update($id, $input);

}
