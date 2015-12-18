<?php namespace App\Http\ViewCreators;
/**
 * Created by PhpStorm.
 * User: pneal
 * Date: 12/08/15
 * Time: 4:11 PM
 */

use Illuminate\Contracts\View\View;
use App\Http\Controllers\UOMControllerInterface;
use \Lang;

class UOMCreator {

    /**
     * The uom repository implementation.
     *
     * @var uomRepository
     */
    protected $uomController;

    /**
     * Create a new profile composer.
     *
     * @param  uomRepository  $users
     * @return void
     */
    public function __construct(
        UOMControllerInterface $uomController
    ) {
        $this->uomController = $uomController;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function create(View $view)
    {
        // request the UOM translations
        $uoms = [Lang::get('labels.enter.UOM')] + $this->uomController->translate('Uom');

        //dd(__METHOD__."(".__LINE__.")",compact('uoms'));
        $view->with('uoms', $uoms);
    }

}