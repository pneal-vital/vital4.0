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

    /**
     * Put a inventory into a tote.
     * ComingleRules verifies move this inventory into this tote is allowed.
     *
     * Returns true if it was successful, otherwise returns an error message.
     */
    public function putInventoryIntoTote($inventoryID, $toteID);
}
