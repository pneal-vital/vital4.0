<?php namespace App\Http\Controllers;



/**
 * Interface PermissionControllerInterface
 * @package App\Http\Controllers
 */
Interface PermissionControllerInterface {

    /**
     * Retrieve a list of the resource.
     */
    public function lists($columnName);

    /**
     * Retrieve a translation of the resource.
     */
    public function translate($columnName);

}
