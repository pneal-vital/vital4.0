<?php namespace App\Http\Controllers;



/**
 * Interface ClientControllerInterface
 * @package App\Http\Controllers
 */
Interface ClientControllerInterface {

    /**
     * Retrieve a list of the resource.
     */
    public function lists($columnName);

    /**
     * Retrieve a translation of the resource.
     */
    public function translate($columnName);

}
