<?php namespace vital3\Repositories;

use App\vital3\InboundOrderDetail;
use App\vital3\InboundOrderDetailAdditional;

class DBInboundOrderDetailRepository implements InboundOrderDetailRepositoryInterface {

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return InboundOrderDetail::get();
        } elseif($limit == 1) {
            return InboundOrderDetail::first();
        }
		return InboundOrderDetail::limit($limit)->get();
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		return InboundOrderDetail::findOrFail($id);
	}

	protected function rawFilter($input) {
		//dd($input);
		// Build a query based on filter $input
		$query = InboundOrderDetail::orderBy('objectID');
		if(isset($input['Order_Number']) && strlen($input['Order_Number']) > 3) {
			$query = $query->where('Order_Number', 'like', $input['Order_Number'] . '%');
		}
		if(isset($input['SKU']) && strlen($input['SKU']) > 3) {
			$query = $query->where('SKU', 'like', $input['SKU'] . '%');
		}
		if(isset($input['Expected_Qty']) && strlen($input['Expected_Qty']) > 0) {
			$query = $query->where('Expected_Qty', 'like', $input['Expected_Qty'] . '%');
		}
		if(isset($input['Actual_Qty']) && strlen($input['Actual_Qty']) > 0) {
			$query = $query->where('Actual_Qty', 'like', $input['Actual_Qty'] . '%');
		}
		if(isset($input['Status']) && strlen($input['Status']) > 1) {
			$query = $query->where('Status', 'like', $input['Status'] . '%');
		}
		if(isset($input['UPC']) && strlen($input['UPC']) > 3) {
			$query = $query->where('UPC', 'like', $input['UPC'] . '%');
		}
		if(isset($input['UOM']) && strlen($input['UOM']) > 3) {
			$query = $query->where('UOM', 'like', $input['UOM'] . '%');
		}
        return $query;
    }

    /**
     * Implement filterOn($input, $limit=10)
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
	 * Implement create($input)
	 */
	public function create($input) {
		return InboundOrderDetail::create($input);
	}

	/**
	 * TODO Consider using Eloquent Relationships
	 * See: https://laracasts.com/series/laravel-5-fundamentals/episodes/14
	 * shows how to configure this when using database migrations.
	 * Example: Auth::user()->articles->save($article);
	 * this example should place user_id (from Auth::user) into $article and save it.
	 */

    /**
     * Implement getAdditional($id)
     */
    public function getAdditional($id) {
        // using the Eloquent model
        return InboundOrderDetailAdditional::whereObjectid($id)->limit(20)->get();
    }
}
