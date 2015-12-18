<?php namespace vital3\Repositories;

use App\vital3\UOM;

class DBUOMRepository implements UOMRepositoryInterface {

    /**
     * Implement lists($limit=10)
     */
    public function lists($limit=10) {
        if($limit == 0) {
            return UOM::get();
        } elseif($limit == 1) {
            return UOM::first();
        }
        return UOM::orderBy('Uom', 'asc')->limit($limit)->get();
    }

    /**
     * Implement filterOn($filter)
     */
    public function filterOn($filter, $limit=10) {
        // Build a query based on filter $filter
        $query = UOM::orderBy('Uom', 'asc');
        if(isset($filter['Uom']) && strlen($filter['Uom']) > 0) {
            $query = $query->where('Uom', $filter['Uom']);
        }
        if($limit == 0) {
            return $query->get();
        } elseif($limit == 1) {
            return $query->first();
        }
        return $query->limit($limit)->get();
    }

}
