<?php namespace App\vital40;
/**
 * Created by PhpStorm.
 * User: pneal
 * Date: 29May2015
 * Time: 11:03 AM
 */

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\View\Factory
 */
class POReconciliationFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
//    protected static function getFacadeAccessor() { return 'receiveArticle'; }
    protected static function getFacadeAccessor() { return 'poReconciliation'; }

}
