<?php namespace App\Http\ViewCreators;
/**
 * Created by PhpStorm.
 * User: pneal
 * Date: 12/08/15
 * Time: 4:11 PM
 */

use Illuminate\Contracts\View\View;
//use Illuminate\Users\Repository as UserRepository;
use \Lang;

class ExportTypeCreator {

    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    //protected $users;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    /*
    public function __construct(UserRepository $users)
    {
        // Dependencies automatically resolved by service container...
        $this->users = $users;
    }
    */

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function create(View $view)
    {
        //dd(__METHOD__."(".__LINE__.")");
        $view->with('exportTypes', [
              '0'   => Lang::get('labels.filter.exportType')
            , 'xls' => Lang::get('labels.exportType.excel')
            , 'csv' => Lang::get('labels.exportType.csv')
        ]);
    }

}