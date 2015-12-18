<?php namespace vital3\Repositories;

use App\vital3\InboundOrder;
use App\vital3\InboundOrderAdditional;
use Carbon\Carbon;

class DBInboundOrderRepository implements InboundOrderRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return InboundOrder::get();
        } elseif($limit == 1) {
            return InboundOrder::first();
        }
		return InboundOrder::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return InboundOrder::findOrFail($id);
	}

	protected function rawFilter($input) {
		// Build a query based on filter $input
		$query = InboundOrder::orderBy('Created', 'desc');
		if(isset($input['Order_Number']) && strlen($input['Order_Number']) > 3) {
			$query = $query->where('Order_Number', 'like', $input['Order_Number'] . '%');
		}
		if(isset($input['Purchase_Order']) && strlen($input['Purchase_Order']) > 3) {
			$query = $query->where('Purchase_Order', 'like', $input['Purchase_Order'] . '%');
		}
		if(isset($input['Invoice_Number']) && strlen($input['Invoice_Number']) > 3) {
			$query = $query->where('Invoice_Number', 'like', $input['Invoice_Number'] . '%');
		}
		if(isset($input['Status']) && strlen($input['Status']) > 1) {
			$query = $query->where('Status', 'like', $input['Status'] . '%');
		}
		if(isset($input['Created']) && strlen($input['Created']) > 6) {
			$query = $query->where('Created', 'like', $input['Created'] . '%');
		}
		if(isset($input['Expected']) && strlen($input['Expected']) > 6) {
			$query = $query->where('Expected', 'like', $input['Expected'] . '%');
		}
        return $query;
    }

    /**
     * Implement filterOn()
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
     * Implement paginate()
     */
    public function paginate($input) {
        return $this->rawFilter($input)->paginate(10);
	}

	/**
	 * Implement getFromDate()
	 */
	public function getFromDate($from) {
		// using the Eloquent model
		//return InboundOrder::latest('Created')->where('Created', '>=', Carbon::now())->get();
		return InboundOrder::latest('Created')->where('Created', '>=', Carbon::parse($from))->get();
	}

	/**
	 * Implement create($input)
	 */
	public function create($input) {
		return InboundOrder::create($input);
	}

	/**
	 * Implement getAdditional($id)
	 */
	public function getAdditional($id) {
		// using the Eloquent model
		return InboundOrderAdditional::whereObjectid($id)->limit(20)->get();
	}
}
