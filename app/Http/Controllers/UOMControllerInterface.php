<?php namespace App\Http\Controllers;



/**
 * Interface UOMControllerInterface
 * @package App\Http\Controllers
 */
Interface UOMControllerInterface {

    /**
     * Retrieve a list of the resource.
     */
    public function lists($columnName);

    /**
     * Retrieve a translation of the resource.
     */
    public function translate($columnName);

}
