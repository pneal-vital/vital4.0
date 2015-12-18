<?php namespace vital40\Repositories;

use vital40\ReceiptHistory;

class DBReceiptHistoryRepository implements ReceiptHistoryRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return ReceiptHistory::get();
        } elseif($limit == 1) {
            return ReceiptHistory::first();
        }
        return ReceiptHistory::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return ReceiptHistory::findOrFail($id);
	}

	public function rawFilter($input) {
		// Build a query based on filter $input
        //dd($input);
		$query = ReceiptHistory::orderBy('activityID', 'desc');
		if(isset($input['PO']) && strlen($input['PO']) > 3) {
			$query = $query->where('PO', 'like', $input['PO'] . '%');
		}
		if(isset($input['POD']) && strlen($input['POD']) > 3) {
			$query = $query->where('POD', 'like', $input['POD'] . '%');
		}
		if(isset($input['Article']) && strlen($input['Article']) > 3) {
			$query = $query->where('Article', 'like', $input['Article'] . '%');
		}
		if(isset($input['UPC']) && strlen($input['UPC']) > 3) {
			$query = $query->where('UPC', 'like', $input['UPC'] . '%');
		}
		if(isset($input['Inventory']) && strlen($input['Inventory']) > 3) {
			$query = $query->where('Inventory', 'like', $input['Inventory'] . '%');
		}
		if(isset($input['Tote']) && strlen($input['Tote']) > 3) {
			$query = $query->where('Tote', 'like', $input['Tote'] . '%');
		}
		if(isset($input['Cart']) && strlen($input['Cart']) > 3) {
			$query = $query->where('Cart', 'like', $input['Cart'] . '%');
		}
		if(isset($input['Location']) && strlen($input['Location']) > 3) {
			$query = $query->where('Location', 'like', $input['location'] . '%');
		}
		if(isset($input['User_Name']) && strlen($input['User_Name']) > 3) {
			$query = $query->where('User_Name', 'like', $input['User_Name'] . '%');
		}
		if(isset($input['created_at']) && strlen($input['created_at']) > 6) {
			$query = $query->where('created_at', 'like', $input['created_at'] . '%');
		}
		if(isset($input['updated_at']) && strlen($input['updated_at']) > 6) {
			$query = $query->where('updated_at', 'like', $input['updated_at'] . '%');
		}
		if(isset($input['Activity']) && strlen($input['Activity']) > 3) {
			$query = $query->where('Activity', 'like', $input['Activity'] . '%');
		}
        return $query;
    }

    /**
     * Implement filterOn($input, $limit)
     */
    public function filterOn($input, $limit=10) {
        if($limit == 0) {
            return $this->rawFilter($input)->get();
        } elseif($limit == 1) {
            return $this->rawFilter($input)->first();
        }
		return $this->rawFilter($input)->limit($limit)->get();
	}

    /**
     * Implement paginate($input)
     */
    public function paginate($input) {
        return $this->rawFilter($input)->paginate(10);
	}

    /**
     * Implement countOn($filter)
     */
    public function countOn($filter) {
        //dd($filter);
        $rHistories = $this->filterOn($filter, $limit=0);
        return count($rHistories);
    }

    /**
	 * Implement create($input)
	 */
	public function create($input) {
		return ReceiptHistory::create($input);
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$receiptHistory = ReceiptHistory::find($id);

		//dd($input);
		return $receiptHistory->update($input);
	}

}
