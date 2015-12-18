<?php namespace App\Http\Controllers;



/**
 * Interface PalletControllerInterface
 * @package App\Http\Controllers
 */
interface PalletControllerInterface {

    /**
     * Get pallet heading from a child's id.
     */
    public function getHeading($id);

    /**
     * Traverse up the hierarchy building heading line
     */
    public function buildHeading($pallet);

}
