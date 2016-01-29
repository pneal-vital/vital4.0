<?php namespace App\Http\ViewCreators;
/**
 * Created by PhpStorm.
 * User: pneal
 * Date: 01/18/16
 * Time: 4:24 PM
 */

use Illuminate\Contracts\View\View;
use App\Http\Controllers\PermissionControllerInterface;
use \Lang;

class PermissionCreator {

    /**
     * The permission repository implementation.
     *
     * @var permissionRepository
     */
    protected $permissionController;

    /**
     * Create a new profile composer.
     *
     * @param  permissionRepository  $users
     * @return void
     */
    public function __construct(
        PermissionControllerInterface $permissionController
    ) {
        $this->permissionController = $permissionController;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function create(View $view)
    {
        // request the Permission translations
        $permissions = [Lang::get('labels.enter.Permission')] + $this->permissionController->translate('display_name');

        //dd(__METHOD__."(".__LINE__.")",compact('permissions'));
        $view->with('permissions', $permissions);
    }

}