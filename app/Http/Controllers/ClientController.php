<?php namespace App\Http\Controllers;

use vital3\Repositories\ClientRepositoryInterface;
use \Lang;

/**
 * Class ClientController
 * @package App\Http\Controllers
 */
class ClientController extends Controller implements ClientControllerInterface {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\ClientRepositoryInterface
	 */ 
	protected $clientRepository;


	/**
	 * Constructor requires Client Repository
	 */ 
	public function __construct(ClientRepositoryInterface $clientRepository) {
		$this->clientRepository = $clientRepository;
	}

    /**
     * Retrieve a list of the resource.
     */
    public function lists($columnName) {

        // using an implementation of the Client Repository Interface
        $clients = $this->clientRepository->lists(100);

        // pull out the requested columnName
        $result = array();
        foreach($clients as $client) {
            $result[ $client['objectID'] ] = $client[$columnName];
        }
        //dd($result);

        // return an array of results
        return $result;
    }

    /**
     * Retrieve a translation of the resource.
     */
    public function translate($columnName) {

        // using an implementation of the Client Repository Interface
        $clients = $this->clientRepository->lists(100);

        // pull out the requested columnName
        $result = array();
        foreach($clients as $client) {
            $result[ $client['objectID'] ] = Lang::get('lists.client.' . $columnName . '.' . $client[$columnName]);
        }
        //dd($result);

        // return an array of results
        return $result;
    }

}
