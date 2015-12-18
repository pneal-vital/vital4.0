<?php namespace App\Http\Controllers;



/**
 * Interface LocationControllerInterface
 * @package App\Http\Controllers
 */
interface LocationControllerInterface {

    /**
     * Get pallet heading from a child's id.
     */
    public function getHeading($id);

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($location);

}
