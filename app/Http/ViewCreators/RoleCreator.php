<?php namespace App\Http\ViewCreators;
/**
 * Created by PhpStorm.
 * User: pneal
 * Date: 12/08/15
 * Time: 4:11 PM
 */

use Illuminate\Contracts\View\View;
use App\Http\Controllers\RoleControllerInterface;
use \Lang;

class RoleCreator {

    /**
     * The role repository implementation.
     *
     * @var roleRepository
     */
    protected $roleController;

    /**
     * Create a new profile composer.
     *
     * @param  roleRepository  $users
     * @return void
     */
    public function __construct(
        RoleControllerInterface $roleController
    ) {
        $this->roleController = $roleController;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function create(View $view)
    {
        // request the Role translations
        $roles = [Lang::get('labels.enter.Role')] + $this->roleController->translate('display_name');

        //dd(__METHOD__."(".__LINE__.")",compact('roles'));
        $view->with('roles', $roles);
    }

}