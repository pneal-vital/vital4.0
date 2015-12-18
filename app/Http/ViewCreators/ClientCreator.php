<?php namespace App\Http\ViewCreators;
/**
 * Created by PhpStorm.
 * User: pneal
 */

use Illuminate\Contracts\View\View;
use App\Http\Controllers\ClientControllerInterface;
use \Lang;

class ClientCreator {

    /**
     * The client repository implementation.
     *
     * @var clientRepository
     */
    protected $clientController;

    /**
     * Create a new profile composer.
     *
     * @param  clientRepository  $users
     * @return void
     */
    public function __construct(
        ClientControllerInterface $clientController
    ) {
        $this->clientController = $clientController;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function create(View $view)
    {
        // request the Client translations
        $clients = [Lang::get('labels.enter.Client')] + $this->clientController->lists('Client_Name');

        //dd(__METHOD__."(".__LINE__.")",compact('clients'));
        $view->with('clients', $clients);
    }

}