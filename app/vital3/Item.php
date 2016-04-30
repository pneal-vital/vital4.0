<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Item extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Item';
	/** primaryKey is objectID */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment objectID */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Item;
	+--------------------+--------------+------+-----+---------+-------+
	| Field              | Type         | Null | Key | Default | Extra |
	+--------------------+--------------+------+-----+---------+-------+
	| objectID           | bigint(20)   | NO   | PRI | NULL    |       |
	| Sku_Number         | varchar(85)  | YES  |     | NULL    |       |
	| Client_Code        | varchar(85)  | YES  | MUL | NULL    |       |
	| Client_SKU         | varchar(85)  | YES  | MUL | NULL    |       |
	| Description        | varchar(255) | YES  |     | NULL    |       |
	| UOM                | varchar(85)  | YES  |     | NULL    |       |
	| Per_Unit_Weight    | varchar(85)  | YES  |     | NULL    |       |
	| Retail_Price       | varchar(85)  | YES  |     | NULL    |       |
	| Case_Pack          | varchar(85)  | YES  |     | NULL    |       |
	| UPC                | varchar(85)  | YES  | MUL | NULL    |       |
	| Colour             | varchar(85)  | YES  |     | NULL    |       |
	| Zone               | varchar(85)  | YES  |     | NULL    |       |
	| Delivery_Number    | varchar(85)  | YES  | MUL | NULL    |       | always ""
	| PO_Number          | varchar(85)  | YES  | MUL | NULL    |       | always ""
	| Description_2      | varchar(255) | YES  |     | NULL    |       |
	| Vendor_Item_Number | varchar(85)  | YES  |     | NULL    |       | always ""
	| Cases_Ordered      | varchar(85)  | YES  |     | NULL    |       | always ""
	| Master_Pack_Cube   | varchar(85)  | YES  |     | NULL    |       | always ""
	| Master_Pack_Weight | varchar(85)  | YES  |     | NULL    |       |
	| Total_Weight       | varchar(85)  | YES  |     | NULL    |       | always ""
	| Total_Cube         | varchar(85)  | YES  |     | NULL    |       | always ""
	+--------------------+--------------+------+-----+---------+-------+
	21 rows in set (0.01 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('Sku_Number', 'Client_Code', 'Client_SKU', 'Description', 'UOM', 'Per_Unit_Weight'
		, 'Retail_Price', 'Case_Pack', 'UPC', 'Colour', 'Zone', 'Description_2', 'Master_Pack_Weight');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('objectID');

	/*
	 * Attributes that should be hidden. In this case these fields are not used, always "".
	 */
	protected $hidden = array('Delivery_Number', 'PO_Number', 'Vendor_Item_Number', 'Cases_Ordered'
		, 'Master_Pack_Cube', 'Total_Weight', 'Total_Cube');

    /**
     * Array to hold _Additional data items
     */
    public $additional = ['opening' => '', 'replen' => '', 'rework' => '', 'split' => ''  // <== used at Article Level
        ,'parents' => []     // <== used at UPC level
    ];

	/**
	 * This function can set objectID, set default values, and validate the entered field values.
	 *
	 * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
	 * or call it from EventServiceProvider::boot(..)
	 */
	public function isCreating() {
		// set objectID
		$inserted = VitalObject::create(['classID' => 'Item']);
		$this->objectID = $inserted->objectID;

		// set default values
		if(isset($this->Per_Unit_Weight) == false || strlen($this->Per_Unit_Weight) < 1)
			$this->Per_Unit_Weight = 0;
		if(isset($this->Retail_Price) == false || strlen($this->Retail_Price) < 1)
			$this->Retail_Price = 0;
		if(isset($this->Case_Pack) == false || strlen($this->Case_Pack) < 1)
			$this->Case_Pack = 0;
		$this->Delivery_Number = "";
		$this->PO_Number = "";
		$this->Vendor_Item_Number = "";
		$this->Cases_Ordered = "";
		$this->Master_Pack_Cube = "";
		if(isset($this->Master_Pack_Weight) == false || strlen($this->Master_Pack_Weight) < 1)
			$this->Master_Pack_Weight = 0;
		$this->Total_Weight = "";
		$this->Total_Cube = "";
		//dd($this);


		/* validate the entered field values.
		if ( ! $this->isValid()) return false;
		*/
		// validation can also be in app\Http\Requests\..Request.php
	}

    /**
     * Using Field Mutators to manage _Additional information about this object
     */
    public function getOpeningAttribute() {         // used in Article level
        $additional = $this->additional;
        //dd(__METHOD__.'('.__LINE__.')',compact('additional'));
        return (isset($this->additional['opening']) ? $this->additional['opening'] : "" );
    }
    public function setOpeningAttribute($value) {
        $this->additional['opening'] = $value;
    }
    public function getReplenAttribute() {         // used in Article level
        return (isset($this->additional['replen']) ? $this->additional['replen'] : "" );
    }
    public function setReplenAttribute($value) {
        $this->additional['replen'] = $value;
    }
    public function getSplitAttribute() {         // used in Article level
        return (isset($this->additional['split']) ? $this->additional['split'] : "" );
    }
    public function setSplitAttribute($value) {
        $this->additional['split'] = $value;
    }
    public function getReworkAttribute() {         // used in UPC level
        return (isset($this->additional['rework']) ? $this->additional['rework'] : "" );
    }
    public function setReworkAttribute($value) {
        $this->additional['rework'] = $value;
    }
    public function getParentsAttribute() {         // used in UPC level
        if(isset($this->additional['parents']) == False)
            $this->additional['parents'] = [];
        return $this->additional['parents'];
    }
    public function setParentsAttribute($value) {
        $this->additional['parents'] = $value;
    }

	public function isSplit() {             // used at Article level
		if(isset($this->additional['split']) and $this->additional['split'] == "N" ) return false;
		return true;
	}
	public function isComingled() {         // used at Article level
		if(isset($this->additional['split']) and $this->additional['split'] == "N" ) return true;
		return false;
	}

	/*
	 * Query Scope, when query building, this type of method is used to build the common parts
	 * Function name format is
	 * 'scope' . capitalize(functionName)
	 * If elsewhere, (possibly our controller) we often have something like this
	 * InboundOrder::where('Status', '=', 'NEW')->get();
	 * it can be replaced with InboundOrder::isNew()->get();
	 * and we can write the scope function as
	 */
	public function scopeIsNew($query) {
		$query->where('Status', '=', 'NEW');
	}
}
