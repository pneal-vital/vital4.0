<?php namespace App\Http\Controllers;



/**
 * Interface WarehouseControllerInterface
 * @package App\Http\Controllers
 */
interface WarehouseControllerInterface {

    /**
     * Get pallet heading from a child's id.
     */
    public function getHeading($id);

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($warehouse);

}
