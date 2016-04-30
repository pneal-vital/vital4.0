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

    /**
     * Put a pallet into a Location.
     * ComingleRules verifies move this pallet into this Location is allowed.
     *
     * Returns true if it was successful, otherwise returns an error message.
     */
    public function putPalletIntoLocation($palletID, $locationID);

}
