<?php namespace App\Http\Controllers;



/**
 * Interface ToteControllerInterface
 * @package App\Http\Controllers
 */
interface ToteControllerInterface {

    /**
     * Get pallet heading from a child's id.
     */
    public function getHeading($id);

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($pallet);

}
