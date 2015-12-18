<?php namespace vital40\Repositories;

use vital40\VendorCompliance;

class DBVendorComplianceRepository implements VendorComplianceRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return VendorCompliance::get();
        } elseif($limit == 1) {
            return VendorCompliance::getFirst();
        }
		return VendorCompliance::limit(10)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return VendorCompliance::findOrFail($id);
	}

	/**
	 * Implement filterOn()
	 */
	public function filterOn($input, $limit=10) {
		// Build a query based on filter $input
		$query = VendorCompliance::orderBy('activityID', 'desc');
        if(isset($input['vendorID']) && strlen($input['vendorID']) > 5) {
            $query = $query->where('vendorID', $input['vendorID']);
        }
		if(isset($input['poID']) && strlen($input['poID']) > 5) {
			$query = $query->where('poID', $input['poID']);
		}
		if(isset($input['podID']) && strlen($input['podID']) > 5) {
			$query = $query->where('podID', $input['podID']);
		}
		if(isset($input['articleID']) && strlen($input['articleID']) > 5) {
			$query = $query->where('articleID', $input['articleID']);
		}
		if(isset($input['upcID']) && strlen($input['upcID']) > 5) {
			$query = $query->where('upcID', $input['upcID']);
		}
		if(isset($input['created_at']) && strlen($input['created_at']) > 6) {
			$query = $query->where('created_at', 'like', $input['created_at'] . '%');
		}
		if(isset($input['updated_at']) && strlen($input['updated_at']) > 6) {
			$query = $query->where('updated_at', 'like', $input['updated_at'] . '%');
		}
        if($limit == 0) {
            return $query->get();
        } elseif($limit == 1) {
            return $query->getFirst();
        }
		return $query->limit($limit)->get();
	}

	/**
	 * Implement create($input)
	 */
	public function create($input) {
		return VendorCompliance::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$userConversation = VendorCompliance::find($id);

		//dd($input);
		return $userConversation->update($input);
	}

}
