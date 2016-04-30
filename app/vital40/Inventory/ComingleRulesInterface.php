<?php namespace App\vital40\Inventory;


/**
 * Interface ComingleRulesInterface
 * @package App\Http\Controllers
 */
interface ComingleRulesInterface {

    /**
     * Verify the movement of this pallet into this location will not break comingling rules.
     *
     * Returns true if allowed, otherwise returns an [error messages].
     * Use === or !== when comparing result with true/false.
     * Error messages are in Lang::get('internal.errors.comingleRules. ..
     */
    public function isPutPalletIntoLocationAllowed($palletID, $locationID);

    /**
     * Verifies the movement of this tote into this pallet will not break comingling rules.
     *
     * Returns true if allowed, otherwise returns an [error messages].
     * Use === or !== when comparing result with true/false.
     * Error messages are in Lang::get('internal.errors.comingleRules. ..
     */
    public function isPutToteIntoPalletAllowed($toteID, $palletID);

    /**
     * Verifies the movement of this inventory into this tote will not break comingling rules.
     *
     * Returns true if allowed, otherwise returns an [error messages].
     * Use === or !== when comparing result with true/false.
     * Error messages are in Lang::get('internal.errors.comingleRules. ..
     */
    public function isPutInventoryIntoToteAllowed($inventoryID, $toteID);
}
