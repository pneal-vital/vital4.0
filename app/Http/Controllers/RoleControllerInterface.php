<?php namespace App\Http\Controllers;



/**
 * Interface RoleControllerInterface
 * @package App\Http\Controllers
 */
Interface RoleControllerInterface {

    /**
     * Retrieve a list of the resource.
     */
    public function lists($columnName);

    /**
     * Retrieve a translation of the resource.
     */
    public function translate($columnName);

}
