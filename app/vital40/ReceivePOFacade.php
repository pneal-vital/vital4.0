<?php namespace App\vital40;
/**
 * Created by PhpStorm.
 * User: pneal
 * Date: 05/03/15
 * Time: 12:11 PM
 */

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\View\Factory
 */
class ReceivePOFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'receivePO'; }

}
