<?php namespace vital3\Repositories;



interface ClientRepositoryInterface {

    public function filterOn($input, $limit=10);

    public function lists($limit=10);

}
