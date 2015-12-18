<?php namespace App\Http\Controllers;

use vital3\Repositories\UOMRepositoryInterface;
use \Lang;

/**
 * Class UOMController
 * @package App\Http\Controllers
 */
class UOMController extends Controller implements UOMControllerInterface {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var vital3\Repositories\UOMRepositoryInterface
	 */ 
	protected $uomRepository;


	/**
	 * Constructor requires UOM Repository
	 */ 
	public function __construct(UOMRepositoryInterface $uomRepository) {
		$this->uomRepository = $uomRepository;
	}

    /**
     * Retrieve a list of the resource.
     */
    public function lists($columnName) {

        // using an implementation of the UOM Repository Interface
        $uoms = $this->uomRepository->lists(100);

        // pull out the requested columnName
        $result = array();
        foreach($uoms as $uom) {
            $result[ $uom['objectID'] ] = $uom[$columnName];
        }
        //dd($result);

        // return an array of results
        return $result;
    }

    /**
     * Retrieve a translation of the resource.
     */
    public function translate($columnName) {

        // using an implementation of the UOM Repository Interface
        $uoms = $this->uomRepository->lists(0);

        // pull out the requested columnName
        $result = array();
        $result[] = Lang::get('lists.uom.Uom.unknown');
        foreach($uoms as $uom) {
            $result[ $uom['objectID'] ] = Lang::get('lists.uom.' . $columnName . '.' . $uom[$columnName]);
        }
        //dd($result);

        // return an array of results
        return $result;
    }

}
