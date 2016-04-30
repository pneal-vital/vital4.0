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

    /**
     * Put a tote into a pallet.
     * ComingleRules verifies move this tote into this pallet is allowed.
     *
     * Returns true if it was successful, otherwise returns an error message.
     */
    public function putToteIntoPallet($toteID, $palletID);

}
