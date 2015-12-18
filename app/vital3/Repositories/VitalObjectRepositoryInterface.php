<?php namespace vital3\Repositories;



interface VitalObjectRepositoryInterface {

    public function find($id);

    public function filterOn($input, $limit=10);

}
