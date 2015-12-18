<?php namespace App\vital40\Receive;
/**
 *
 * Created by PhpStorm.
 * User: pneal
 * Date: 05/03/15
 * Time: 12:03 PM
 */

use Illuminate\Support\Facades\Lang;
use vital3\Repositories\LocationRepositoryInterface;
use vital40\Repositories\UserActivityRepositoryInterface;
use \Config;


class LocationFlow {

    /**
     * Reference an implementation of the Repository Interface
     * @var vital40\Repositories\LocationRepositoryInterface
     */
    protected $locationRepository;
    protected $userActivityRepository;


    /**
     * Constructor requires location Repository
     */
    public function __construct(LocationRepositoryInterface $locationRepository
            , UserActivityRepositoryInterface $userActivityRepository) {
        $this->locationRepository = $locationRepository;
        $this->userActivityRepository = $userActivityRepository;
    }

    public function associate($location) {
        //dd($location);

        $this->userActivityRepository->associate($location->objectID
                                                , Config::get('constants.userActivity.classID.ReceiveLocation')
                                                , Lang::get('internal.userActivity.purpose.receiveLocation', ['name' => $location->Location_Name]));

    }

}
