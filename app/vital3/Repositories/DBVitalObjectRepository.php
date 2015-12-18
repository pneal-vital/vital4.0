<?php namespace vital3\Repositories;

use App\vital3\VitalObject;

class DBVitalObjectRepository implements VitalObjectRepositoryInterface {

    /**
     * Implement find($id)
     */
    public function find($id) {
        return VitalObject::find($id);
    }

    /**
     * Implement filterOn($filter)
     */
    public function filterOn($filter, $limit=10) {
        // Build a query based on filter $filter
        $query = VitalObject::orderBy('objectID', 'asc');
        if(isset($filter['classID']) && strlen($filter['classID']) > 0) {
            $query = $query->where('classID', $filter['classID']);
        }
        if($limit == 0) {
            return $query->get();
        } elseif($limit == 1) {
            return $query->first();
        }
        return $query->limit($limit)->get();
    }

}
