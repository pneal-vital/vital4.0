<?php namespace vital40\Repositories;


interface PurchaseOrderRepositoryInterface {

	public function getAll($limit=10);

    /**
     * Here we find a PurchaseOrder by Purchase_Order number
     * @param $id
     * @return mixed
     */
	public function find($id);

    /**
     * Here we find a PurchaseOrder by Inbound_Order.objectID
     * @param $id
     * @return mixed
     */
	public function findID($id);

	public function filterOn($filter, $limit=10);

	public function paginate($filter);

    public function update($id, $input);

}
