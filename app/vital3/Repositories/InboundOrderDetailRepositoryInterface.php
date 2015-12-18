<?php namespace vital3\Repositories;



interface InboundOrderDetailRepositoryInterface {

	public function getAll($limit=10);

	public function find($id);

	public function filterOn($input, $limit=10);

	public function paginate($input);

	public function create($input);

    // functions related to Inbound_Order_Detail_Additional
    public function getAdditional($id);
}
