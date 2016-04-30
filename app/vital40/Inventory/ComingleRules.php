<?php namespace App\vital40\Inventory;

use vital3\Repositories\InventoryRepositoryInterface;
use vital3\Repositories\LocationRepositoryInterface;
use vital3\Repositories\PalletRepositoryInterface;
use vital40\Repositories\ArticleRepositoryInterface;
use vital40\Repositories\ToteRepositoryInterface;
use vital40\Repositories\UPCRepositoryInterface;
use \Lang;
use \Log;

/**
 * Class ComingleRules
 * @package App\Http\Controllers
 */
class ComingleRules implements ComingleRulesInterface {

	/**
	 * Reference an implementation of the Repository Interface
	 * @var LocationRepositoryInterface
	 */
	protected $articleRepository;
	protected $inventoryRepository;
	protected $locationRepository;
	protected $palletRepository;
    protected $toteRepository;
    protected $upcRepository;

	/**
	 * Constructor requires Location Repository
	 */ 
	public function __construct(
          ArticleRepositoryInterface $articleRepository
        , InventoryRepositoryInterface $inventoryRepository
        , LocationRepositoryInterface $locationRepository
        , PalletRepositoryInterface $palletRepository
        , ToteRepositoryInterface $toteRepository
        , UPCRepositoryInterface $upcRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->locationRepository = $locationRepository;
        $this->palletRepository = $palletRepository;
        $this->toteRepository = $toteRepository;
        $this->upcRepository = $upcRepository;
	}

    /**
     * Verify the movement of this pallet into this location will not break comingling rules.
     *
     * Returns true if allowed, otherwise returns an [error messages].
     * Use === or !== when comparing result with true/false.
     * Error messages are in Lang::get('internal.errors.comingleRules.__
     */
	public function isPutPalletIntoLocationAllowed($palletID, $locationID) {
        Log::debug("isPutPalletIntoLocationAllowed( $palletID, $locationID )");
        $errors = [];
        $pallet = $this->palletRepository->find($palletID);
        if(isset($pallet) == false) {
            $errors[] = Lang::get('internal.errors.notFound', ['classID' => 'Pallet', 'objectID' => $palletID]);
        }
        $location = $this->locationRepository->find($locationID);
        if(isset($location) == false) {
            $errors[] = Lang::get('internal.errors.notFound', ['classID' => 'Location', 'objectID' => $locationID]);
        }
        if(count($errors) > 0) return $errors;

        /*
         * Comingling rules
         * ================
         * Comingling rules apply at the putting inventory into tote level.
         *
         * When asked to put a pallet into a location the following rules should be observed.
         * 1. Anything can be moved into an activity (includes TZone) location.
         * 2. An empty Pallet (does not contain inventory) may be put anywhere.
         * 3. Anything in reserve or an activity location may be moved to a reserve location.
         * 4. Any pallet in a pick face location can be moved into another pick face location.
         */

        // calculate the locations LocType
        $locations_LocType = $location->LocType;
        if(substr($locations_LocType, 0, 4) == 'PICK') $locations_LocType = 'PICK';
        Log::debug('locations LocType: '.$locations_LocType);

        // 1. Anything can be moved into an activity (includes TZone) location
        if($locations_LocType == 'ACTIVITY') {
            Log::debug('1. Moved into an activity location');
            return true;
        }

        // 2. An empty Pallet (does not contain inventory) may be put anywhere.
        $pltTotes = $this->toteRepository->filterOn(['container.parent' => $palletID], 0);
        if(isset($pltTotes) == false or count($pltTotes) == 0) {
            Log::debug('2a. An empty Pallet (no totes) may be put anywhere.');
            return true;
        } else {
            $invCount = 0;
            foreach($pltTotes as $pltTote) {
                $invCount += $this->inventoryRepository->quantityOn(['container.parent' => $pltTote->objectID]);
            }
            if($invCount == 0) {
                Log::debug('2b. An empty Pallet (empty totes) may be put anywhere.');
                return true;
            }
        }

        // calculate the Pallets LocType
        $pallets_LocType = $this->getPalletsLocType($pallet);

        // 3. Anything in reserve or an activity location can be moved to a reserve location
        if($locations_LocType == 'RESERVE') {
            if($pallets_LocType == 'RESERVE') {
                Log::debug('3a. Reserve to reserve');
                return true;
            } elseif($pallets_LocType == 'ACTIVITY') {
                Log::debug('3b. Activity to reserve');
                return true;
            }
            $errors[] = Lang::get('internal.errors.comingleRules.reserve');
            return $errors;
        }

        // 4. Any pallet in a pick face location can be moved into another pick face location.
        if($pallets_LocType == 'PICK' and $locations_LocType == 'PICK') {
            Log::debug('4. move pick into pick face location');
            return true;
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('palletID','locationID','pallet','location','pltTotes','pltLocs','pallets_LocType','locations_LocType','errors'));

        Log::warning("Cannot move from pallet locType $pallets_LocType, into LocType $locations_LocType");
        return [Lang::get('internal.errors.comingleRules.notMoveLocType', ['from' => $pallets_LocType, 'to' => $locations_LocType])];
	}

    /**
     * Calculate a Pallets LocType
     * @param $pallet
     * @return string
     */
    protected function getPalletsLocType($pallet) {
        $pltLocs = $this->locationRepository->filterOn(['container.child' => $pallet->objectID], 2);
        $result = 'unlocated';
        if(isset($pallet->Pallet_ID) and strlen($pallet->Pallet_ID) > 3) {
            /*
             * On a FWP cart, it should default to an activity location.
             * gunApp PutAway task can move the inventory into a pick face
             * or put it back up in reserve
             */
            if(substr($pallet->Pallet_ID,0,3) == 'FWP') $result = 'ACTIVITY';
            if(substr($pallet->Pallet_ID,0,3) == 'RES') $result = 'RESERVE';
        }
        if(isset($pltLocs) and count($pltLocs) > 0) $result = $pltLocs[0]->LocType;
        if(substr($result,0,4) == 'PICK') $result = 'PICK';
        Log::debug('pallets LocType: '.$result);
        return $result;
    }

    /**
     * Verifies the movement of this tote into this pallet will not break comingling rules.
     *
     * Returns true if allowed, otherwise returns an [error messages].
     * Use === or !== when comparing result with true/false.
     * Error messages are in Lang::get('internal.errors.comingleRules.__
     */
    public function isPutToteIntoPalletAllowed($toteID, $palletID) {
        Log::debug("isPutToteIntoPalletAllowed( $toteID, $palletID )");
        $errors = [];
        $tote = $this->toteRepository->find($toteID);
        if(isset($tote) == false) {
            $errors[] = Lang::get('internal.errors.notFound', ['classID' => 'Tote', 'objectID' => $toteID]);
        }
        $pallet = $this->palletRepository->find($palletID);
        if(isset($pallet) == false) {
            $errors[] = Lang::get('internal.errors.notFound', ['classID' => 'Pallet', 'objectID' => $palletID]);
        }
        if(count($errors) > 0) return $errors;

        /*
         * Comingling rules
         * ================
         * Comingling rules apply at the putting inventory into tote level.
         *
         * When asked to put a Tote onto a Pallet the following rules should be observed.
         * 1. Anything can be moved into an activity (includes TZone) location.
         * 2. An empty Tote (does not contain inventory) may be put anywhere.
         * 3. Anything in reserve or an activity location may be moved to a reserve location.
         * 4. Any tote in a pick face location may be moved onto another pallet in a pick face location.
         */

        // calculate the Pallets LocType
        $pallets_LocType = $this->getPalletsLocType($pallet);

        // 1. Anything can be moved into an activity (includes TZone) location.
        if($pallets_LocType == 'ACTIVITY') {
            Log::debug('1. Moved into an activity location');
            return true;
        }

        // 2. An empty Tote (does not contain inventory) may be put anywhere.
        $isEmpty = $this->toteRepository->isEmpty($toteID);
        if($isEmpty) {
            Log::debug('2. An empty Tote may be put anywhere.');
            return true;
        }

        // calculate the Totes LocType
        $totes_LocType = $this->getTotesLocType($tote);

        // 3. Anything in reserve or an activity location may be moved to a reserve location.
        if($pallets_LocType == 'RESERVE') {
            if($totes_LocType == 'RESERVE') {
                Log::debug('3a. Reserve to reserve');
                return true;
            } elseif($totes_LocType == 'ACTIVITY') {
                Log::debug('3b. Activity to reserve');
                return true;
            }
            $errors[] = Lang::get('internal.errors.comingleRules.reserve');
            return $errors;
        }

        // 4. Any tote in a pick face location may be moved onto another pallet in a pick face location.
        if($totes_LocType == 'PICK' and $pallets_LocType == 'PICK') {
            Log::debug('4. move pick into pick face location');
            return true;
        }

        //dd(__METHOD__.'('.__LINE__.')',compact('toteID','palletID','tote','pallet','pltLocs','pallets_LocType','invCount','totePlts','totes_LocType','errors'));
        Log::warning("Cannot move from tote locType $totes_LocType, into pallet LocType $pallets_LocType");
        return [Lang::get('internal.errors.comingleRules.notMoveLocType', ['from' => $totes_LocType, 'to' => $pallets_LocType])];
    }

    /**
     * Calculate a Totes LocType
     * @param $tote
     * @return string
     */
    protected function getTotesLocType($tote) {
        $totePlts = $this->palletRepository->filterOn(['container.child' => $tote->objectID], 2);
        $result = 'unlocated';
        if(isset($totePlts) == false or count($totePlts) == 0)
            Log::warning('this->palletRepository->filterOn([\'container.child\' => '.$tote->objectID.'], 2); returns no pallets!');
        if(isset($totePlts) and count($totePlts) > 0) {
            $result = $this->getPalletsLocType($totePlts[0]);
        }
        Log::debug(' totes LocType: '.$result);
        return $result;
    }

    /**
     * Verifies the movement of this inventory into a tote will not break comingling rules.
     *
     * Returns true if allowed, otherwise returns an [error messages].
     * Use === or !== when comparing result with true/false.
     * Error messages are in Lang::get('internal.errors.comingleRules. ..
     */
    public function isPutInventoryIntoToteAllowed($inventoryID, $toteID) {
        Log::debug("isPutInventoryIntoToteAllowed( $inventoryID, $toteID )");

        $errors = [];
        $inventory = $this->inventoryRepository->find($inventoryID);
        if(isset($inventory) == false) {
            $errors[] = Lang::get('internal.errors.notFound', ['classID' => 'Inventory', 'objectID' => $inventoryID]);
        }
        $tote = $this->toteRepository->find($toteID);
        if(isset($tote) == false) {
            $errors[] = Lang::get('internal.errors.notFound', ['classID' => 'Tote', 'objectID' => $toteID]);
        }
        if(count($errors) > 0) return $errors;

        /*
         * Comingling rules
         * ================
         * Comingling rules apply at the putting inventory into tote level.
         *
         * When asked to put Inventory into a Tote the following rules should be observed.
         * It must adhere to the Comingling at the Inventory Level Rules
         * and must adhere to the Inventory Movement at the LocType Level Rules
         */

        $result = $this->cominglingAtTheInventoryLevelRules($inventory, $tote);
        if($result !== true) return $result;

        $result = $this->inventoryMovementAtTheLocTypeLevelRules($inventory, $tote);
        if($result !== true) return $result;

        return true;
    }

    /**
     * Comingling at the Inventory Level Rules
     * 1. Inventory can be placed into an empty tote.
     * 2. Inventory can be placed into a tote that contains ONLY that same Item/UPC.
     * 3. Comingleable Inventory can be placed into a tote that contains Inventory of the same Article
     * 4. Comingleable Inventory can be placed into a tote that contains Inventory of any comingled Article that includes this same Item/UPC.
     */
    public function cominglingAtTheInventoryLevelRules($inventory, $tote) {
        Log::debug("cominglingAtTheInventoryLevelRules( $inventory->objectID, $tote->objectID )");

        // 1. Inventory can be placed into an empty tote.
        $isEmpty = $this->toteRepository->isEmpty($tote->objectID);
        if($isEmpty) {
            Log::debug('1. Inventory can be placed into an empty tote.');
            return true;
        }

        /*
         * Ok, so what are we doing here.
         * Consider a case where there are multiple articles for our Inventory UPC
         *
         * Article A has 2 of UPC ua, 2 of UPC ub and 2 of UPC uc
         * Article B has 1 of UPC ua and 3 of UPC ub
         * Article C has 3 of UPC ub, 1 of UPC uc and 1 of UPC ud
         *
         * Our Inventory is for UPC ua
         *
         * Rule 2. catches the case when our tote has UPCs ua
         * Rule 3. catches the cases when our tote has UPCs ua and/or ub and/or uc
         * Rule 4. catches the case when our tote has UPC ud with or without UPCs ua, ub or uc
         */

        // 2. Inventory can be placed into a tote that contains ONLY that same Item/UPC.
        $toteUPCs = $this->upcRepository->getToteUPCs($tote->objectID, 0);
        if(isset($toteUPCs) == false or count($toteUPCs) == 0)
            Log::warning('this->upcRepository->getToteUPCs('.$tote->objectID.', 0); produced no Inventories!');
        if(isset($toteUPCs) and count($toteUPCs) == 1 and $toteUPCs[0]->objectID == $inventory->Item) {
            Log::debug('2. Inventory can be placed with same Item/UPC.');
            return true;
        }

        // is this inventory comingled?
        Log::debug('is this inventory comingled?');
        $isComingled = $this->checkIfComingled([$inventory->Item]);
        if($isComingled) Log::debug('inventory\'s article is comingled');

        // Retrieve the UPCs of our inventories articles.
        $upcs_of_invArticles = [];
        if($isComingled) {
            Log::debug('Retrieve the UPCs of our inventories articles.');
            $upcs_of_invArticles = $this->explodeUPCs([$inventory->Item]);
        }

        // 3. Comingleable Inventory can be placed into a tote that contains Inventory of the same Article
        if($isComingled) {
            // are all the upcs within our tote, contained in $upcs_of_invArticles ?
            $allFound = true;
            if(isset($toteUPCs) and count($toteUPCs) > 0) {
                foreach($toteUPCs as $toteUPC) {
                    Log::debug('totes UPCs : '.$toteUPC->objectID);
                    if(isset($upcs_of_invArticles[$toteUPC->objectID]) == false)  $allFound = false;
                }
            } else {
                Log::debug('defaulting to not allFound false');
                $allFound = false;
            }
            if($allFound) {
                Log::debug('3. Comingle Inventory with Inventory of the same Article');
                return true;
            }
        }

        // Retrieve the UPCs of the inventory in our tote.
        $upcs_allowed_in_this_tote = [];
        if($isComingled) {
            if(isset($toteUPCs) and count($toteUPCs) > 0) {
                Log::debug('Retrieve the UPCs of the inventory in our tote.');
                // convert array of UPCs into an array of upcIDs
                $upcIDs = [];
                foreach($toteUPCs as $toteUPC) {
                    $upcIDs[] = $toteUPC->objectID;
                }
                $upcs_allowed_in_this_tote = $this->explodeUPCs($upcIDs);
                // explodeUPCs has added other UPCs to our list, check one more time for beyond additional UPCs
                $upcIDs_diff = array_diff(array_keys($upcs_allowed_in_this_tote), $upcIDs);
                if(isset($upcIDs_diff) and count($upcIDs_diff) > 0) {
                    Log::debug('Checking beyond for additional UPCs to allow');
                    $upcs_allowed_in_this_tote = array_merge($upcs_allowed_in_this_tote, $this->explodeUPCs($upcIDs_diff));
                }
            }
        }

        // 4. Comingleable Inventory can be placed into a tote that contains Inventory of any comingled Article that includes this same Item/UPC.
        if($isComingled) {
            $isFound = false;
            foreach($upcs_allowed_in_this_tote as $upc) {
                Log::debug('upcs_allowed_in_this_tote '.$upc->objectID.' == inventory->Item '.$inventory->Item);
                if($upc->objectID == $inventory->Item) {
                    $isFound = true;
                    break;
                }
            }
            if($isFound) {
                Log::debug('4. Comingle Inventory with Inventory of the combined Articles');
                return true;
            }
        }

        //dd(__METHOD__.'('.__LINE__.')',compact('inventory','tote','isEmpty','toteUPCs','invArticles','isComingled'));
        $errors[] = Lang::get('internal.errors.comingleRules.notMoveComingle', ['invComingled' => ($isComingled ? 'comingled' : 'none comingled'), 'Carton_ID' => $tote->Carton_ID]);
        return $errors;
    }

    /**
     * Retrieve the UPCs of provided UPCs articles.
     * From parameter UPCs,
     * - go up to all the articles that have these UPCs,
     * - go back down to UPCs of all these articles
     * - return $articlesUPCs[$upc->objectID] = $upc;
     * @param $upcIDs, array of UPCs to explode
     * @return array of UPCs [keyed on objectID]
     */
    protected function explodeUPCs($upcIDs) {
        $result = [];
        // are we given at least one UPC->objectID
        if(isset($upcIDs) and count($upcIDs) > 0) {
            foreach($upcIDs as $inUPC) {
                $articles = $this->articleRepository->getUPCArticles(''.$inUPC, 0);
                if(isset($articles) == false or count($articles) == 0)
                    Log::warning('this->articleRepository->getUPCArticles('.$inUPC.', 0); produced no articles!');
                if(isset($articles) and count($articles) > 0) {
                    foreach($articles as $article) {
                        $upcs = $this->upcRepository->getArticleUPCs($article->objectID, 0);
                        if(isset($upcs) == false or count($upcs) == 0)
                            Log::warning('this->upcRepository->getArticleUPCs('.$article->objectID.', 0); produced no UPCs!');
                        if(isset($upcs) and count($upcs) > 0) {
                            foreach($upcs as $upc) {
                                Log::debug('UPCs of '.$inUPC.' articles: '.$upc->objectID);
                                $result[$upc->objectID] = $upc;
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Check articles of provided UPCs, are they all comingled?
     * @param $upcIDs, array of UPCs to explode
     * @return false if any article is found that is not comingled
     */
    protected function checkIfComingled($upcIDs) {
        $isComingled = true;
        // are we given at least one UPC->objectID
        if(isset($upcIDs) and count($upcIDs) > 0) {
            foreach($upcIDs as $inUPC) {
                $articles = $this->articleRepository->getUPCArticles($inUPC, 0);
                if(isset($articles) == false or count($articles) == 0)
                    Log::warning('this->articleRepository->getUPCArticles('.$inUPC.', 0); produced no articles!');
                if(isset($articles) and count($articles) > 0) {
                    foreach($articles as $article) {
                        Log::debug('article: '.$article->objectID.($article->isSplit() ? ' is split' : ' is comingled'));
                        if($article->isSplit()) {
                            $isComingled = false;
                            break 2;
                        }
                    }
                } else {
                    Log::debug('defaulting to is split');
                    $isComingled = false;
                    break;
                }
            }
        }
        return $isComingled;
    }


    /**
     * Inventory Movement at the LocType Level Rules
     * 1. Anything can be moved into an activity (includes TZone) location.
     * 2. Anything in reserve or an activity location can be moved to a reserve location.
     * 3. Inventory in a pick face location can be moved into another tote in a pick face location.
     */
    public function inventoryMovementAtTheLocTypeLevelRules($inventory, $tote) {
        Log::debug("inventoryMovementAtTheLocTypeLevelRules( $inventory->objectID, $tote->objectID )");

        // calculate the Totes LocType
        $totes_LocType = $this->getTotesLocType($tote);

        // 1. Anything can be moved into an activity (includes TZone) location.
        if($totes_LocType == 'ACTIVITY') {
            Log::debug('1. Moved into an activity location');
            return true;
        }

        // calculate the Inventory's LocType
        $inventorys_LocType = $this->getInventoryLocType($inventory);

        // 2. Anything in reserve or an activity location can be moved to a reserve location.
        if($totes_LocType == 'RESERVE') {
            if($inventorys_LocType == 'RESERVE') {
                Log::debug('2a. Reserve to reserve');
                return true;
            } elseif($inventorys_LocType == 'ACTIVITY') {
                Log::debug('2b. Activity to reserve');
                return true;
            }
            $errors[] = Lang::get('internal.errors.comingleRules.reserve');
            return $errors;
        }

        // 3. Inventory in a pick face location can be moved into another tote in a pick face location.
        if($inventorys_LocType == 'PICK' and $totes_LocType == 'PICK') {
            Log::debug('3. move pick into pick face location');
            return true;
        }

        $errors[] = Lang::get('internal.errors.comingleRules.notMoveLocType');
        return $errors;
    }

    /**
     * Calculate an Inventory's LocType
     * @param $inventory
     * @return string
     */
    protected function getInventoryLocType($inventory) {
        $invTotes = $this->toteRepository->filterOn(['container.child' => $inventory->objectID], 2);
        $result = 'unlocated';
        if(isset($invTotes) == false or count($invTotes) == 0)
            Log::warning('this->toteRepository->filterOn([\'container.child\' => '.$inventory->objectID.'], 2); returns no totes!');
        if(isset($invTotes) and count($invTotes) > 0) {
            $result = $this->getTotesLocType($invTotes[0]);
        }
        Log::debug('  inventory\'s LocType: '.$result);
        return $result;
    }
}
