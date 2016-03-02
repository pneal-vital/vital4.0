<?php namespace vital40\Repositories;

use App\vital3\Item;
use App\vital3\ItemKit;
use App\vital3\ItemAdditional;
use Illuminate\Support\Facades\DB;
use \Log;

class DBArticleRepository implements ArticleRepositoryInterface {

	/**
	 * Basic select to retrieve Article data.
	 * @return mixed
	 */
	private function articleSelect() {
        // using the Eloquent model
		return Item::query()
			->select('Item.*')
			->join('itemKit', 'itemKit.parentID', '=', 'Item.objectID')
			->distinct();
	}

	/**
	 * Implement getAll()
	 */
	public function getAll($limit=10) {
		// using the Eloquent model
        if($limit == 0) {
            return $this->findAdditional($this->articleSelect()->get());
        } elseif($limit == 1) {
            return $this->findAdditional($this->articleSelect()->first());
        }
		return $this->findAdditional($this->articleSelect()->limit($limit)->get());
	}

	/**
	 * Implement find($id)
	 */
	public function find($id) {
		// using the Eloquent model
		$article = $this->articleSelect()
			->where('Item.objectID', '=', $id)
			->first();

        $this->findAdditional($article);

        //dd(__METHOD__.'('.__LINE__.')',compact('id','article'));
		return $article;
    }

    private function findAdditional($container) {
        /*
         * Here we want to change the contents within the $container, not the $container,
         * so retrieve/setup $items as an array_expression holding those contents
         */
        $items = $container;
        // if $container is a paginator
        if(is_a($container, 'Illuminate\Pagination\LengthAwarePaginator')) {
            $items = $container->items();
        }
        // if $items is not, make it an array
        elseif(!is_array($container) && !is_a($container, 'Illuminate\Support\Collection')) {
            $items = array($container);
        }
        //dd(__METHOD__."(".__LINE__.")",compact('container','items'));
        Log::debug(__METHOD__."(".__LINE__."):  container class: ".get_class($container));
        Log::debug(__METHOD__."(".__LINE__."):  items class: ".(is_array($items) ? "array(".count($items).")" : get_class($items)));

        foreach($items as $article) {
            if(isset($article)) {  // could be null if find($id) did not find an article
                /*
                 * Add these fields from Item_Additional
                    | opening            | varchar(85)  | YES  |     | NULL    |       |
                    | replen             | varchar(85)  | YES  |     | NULL    |       |
                    | rework             | varchar(85)  | YES  |     | NULL    |       |
                    | split              | varchar(85)  | YES  |     | NULL    |       |
                 */
                $additionals = ItemAdditional::where('objectID', $article->objectID)->get();
                foreach($additionals as $additional) {
                    $article->$additional['Name'] = $additional['Value'];
                }
                //dd(__METHOD__.'('.__LINE__.')',compact('container','items','article','additionals'));
            }
        }

        return $container;
    }

	protected function rawFilter($filter) {
		// Build a query based on filter $input
		$query = DBArticleRepository::articleSelect();
        if(isset($filter['objectID']) && strlen($filter['objectID']) > 3) {
            $query->where('Item.objectID', '=', $filter['objectID']);
        }
		if(isset($filter['Sku_Number']) && strlen($filter['Sku_Number']) > 3) {
			$query->where('Item.Sku_Number', 'like', trim($filter['Sku_Number']) . '%');
		}
		if(isset($filter['Client_Code']) && strlen($filter['Client_Code']) > 3) {
			$query->where('Item.Client_Code', $filter['Client_Code']);
		}
		if(isset($filter['Client_SKU']) && strlen($filter['Client_SKU']) > 3) {
			$query->where('Item.Client_SKU', 'like', $filter['Client_SKU'] . '%');
		}
		if(isset($filter['Description']) && strlen($filter['Description']) > 3) {
			$query->where('Item.Description', 'like', $filter['Description'] . '%');
		}
		if(isset($filter['UPC']) && strlen($filter['UPC']) > 3) {
			$query->where('Item.UPC', 'like', ltrim($filter['UPC'],'0') . '%');
		}
		if(isset($filter['UOM']) && strlen($filter['UOM']) > 3) {
			$query->where('Item.UOM', '=', $filter['UOM']);
		}
		if(isset($filter['Colour']) && strlen($filter['Colour']) > 1) {
			$query->where('Item.Colour', 'like', $filter['Colour'] . '%');
		}
		if(isset($filter['Zone']) && strlen($filter['Zone']) > 3) {
			$query->where('Item.Zone', 'like', $filter['Zone'] . '%');
		}
        return $query;
    }

    /**
     * Implement filterOn()
     */
    public function filterOn($filter, $limit=10) {
        if($limit == 0) {
            return $this->findAdditional($this->rawFilter($filter)->get());
        } elseif($limit == 1) {
            return $this->findAdditional($this->rawFilter($filter)->first());
        }
		return $this->findAdditional($this->rawFilter($filter)->limit($limit)->get());
	}

    /**
     * Implement paginate($input)
     */
    public function paginate($filter) {
        $results = $this->rawFilter($filter)->paginate(10);
        $this->findAdditional($results);
        //dd(__METHOD__.'('.__LINE__.')',compact('filter', 'results'));
        return $results;
    }

    /**
     * Get the PODs and Articles for this poID
     * @param $poID
     * @return mixed
     */
    public function getPODArticles($poID, $limit=10) {
        $query = DB::connection('vitaldev')
            ->table('Item as Article')
            ->join('Inbound_Order_Detail', 'Inbound_Order_Detail.SKU', '=', 'Article.objectID')
            ->leftJoin('Item_Additional', function ($query) {
                $query->on('Item_Additional.objectID', '=', 'Article.objectID')
                      ->on('Item_Additional.Name', '=', DB::raw("'rework'"));
            })
            ->distinct()
            ->select('Article.objectID as articleID', 'Article.Client_SKU', 'Article.Description', 'Article.UOM', 'Article.Case_Pack', 'Article.Colour', 'Article.Zone'
                ,'Inbound_Order_Detail.objectID as purchaseOrderDetailID', 'Inbound_Order_Detail.Expected_Qty'
                ,'Item_Additional.Value as rework')
            ->where('Inbound_Order_Detail.Order_Number', '=', $poID)
            ->orderBy('Article.Client_SKU')
            ->orderBy('Article.objectID');
        if($limit == 0) {
            return $query->get();
        } elseif($limit == 1) {
            return $query->first();
        }
        return $query->limit($limit)->get();
    }

	/**
	 * Implement create($input)
	 */
	public function create($input) {
		$article = Item::create($input);
		ItemKit::create(['parentID' => $article->objectID, 'objectID' => '0', 'Quantity' => '0']);

        // update here to add the _additional attributes
        $updatedArticle = $this->update($article->objectID, $input);

		//dd(__METHOD__.'('.__LINE__.')',compact('input','article','updatedArticle'));
		return $updatedArticle;
	}

	/**
	 * Implement update($id, $input)
	 */
	public function update($id, $input) {
		$item = $this->find($id);

		$updatedArticle = $item->update($input);

        foreach($item->additional as $name => $value) {
            if(isset($input[$name])) {
                $additional = ItemAdditional::where('objectID', $id)->where('Name', $name)->first();
                if(isset($additional)) {
                    if($value != $input[$name]) {
                        $additional->update(['Value' => $input[$name]]);
                    }
                } else {
                    ItemAdditional::create(['objectID' => $id, 'Name' => $name, 'Value' => $input[$name]]);
                }
            }
        }
        //dd(__METHOD__.'('.__LINE__.')',compact('id', 'input', 'item','updatedItem'));

        return $updatedArticle;
    }

    /**
     * Implement delete($id)
     */
    public function delete($id) {
        $deleted = true;
        $item = $this->find($id);

        if(isset($item)) {
            //dd(__METHOD__.'('.__LINE__.')',compact('id','item'));
            ItemAdditional::destroy($item->objectID);
            $deleted = $item->delete();
        }

        return $deleted;
	}

}
