<?php namespace vital3\Repositories;



interface UOMRepositoryInterface {

    public function lists($limit=10);

    public function filterOn($input, $limit=10);

}
