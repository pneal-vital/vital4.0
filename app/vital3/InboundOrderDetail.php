<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InboundOrderDetail extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Inbound_Order_Detail';
	/** primaryKey is objectID */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment objectID */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Inbound_Order_Detail;
	+--------------+-------------+------+-----+---------+-------+
	| Field        | Type        | Null | Key | Default | Extra |
	+--------------+-------------+------+-----+---------+-------+
	| objectID     | bigint(20)  | NO   | PRI | NULL    |       |
	| Order_Number | varchar(85) | YES  | MUL | NULL    |       |
	| Line_Number  | varchar(85) | YES  |     | NULL    |       | always ""
	| SKU          | varchar(85) | YES  | MUL | NULL    |       | contains Article.objectID
	| Expected_Qty | varchar(85) | YES  |     | NULL    |       |
	| Actual_Qty   | varchar(85) | YES  |     | NULL    |       |
	| Status       | varchar(85) | YES  | MUL | NULL    |       |
	| Received     | varchar(85) | YES  |     | NULL    |       | always ""
	| UPC          | varchar(35) | YES  | MUL | NULL    |       |
	| UCC          | varchar(35) | YES  |     | NULL    |       | always ""
	| UOM          | varchar(85) | YES  |     |         |       |
	+--------------+-------------+------+-----+---------+-------+
	11 rows in set (0.05 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('SKU', 'Expected_Qty', 'Actual_Qty', 'Status', 'UPC', 'UOM');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('objectID', 'Order_Number');

	/*
	 * Attributes that should be hidden. In this case these fields are not used, always "".
	 */
	protected $hidden = array('Line_Number', 'Received', 'UCC');

    /**
     * Array to hold _Additional data items
     */
    public $additional = ['Location' => ''];


	/**
	 * This function can set objectID, set default values, and validate the entered field values.
	 *
	 * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
	 * or call it from EventServiceProvider::boot(..)
	 */
	public function isCreating() {
		// set objectID
		$inserted = VitalObject::create(['classID' => 'Inbound_Order_Detail']);
		$this->objectID = $inserted->objectID;

		// set default values
		$this->Line_Number = "";
		$this->Status = "NEW";
		$this->Received = "";
		$this->UCC = "";

		/* validate the entered field values.
		if ( ! $this->isValid()) return false;
		*/
	}

	/*
	 * Field Mutators, used to validate, manipulate the value on set
	 * TODO Add get/set SKU Attribute(..) - verify objectID is found in Item.objectID
	 * TODO Add get/set Status Attribute(..) - value must be valid, see STD.
	 * Function name format is
	 * 'set' . CamileCase(fieldName) . 'Attribute'
	 * as in setNameAttribute() { .. }
	 */
    /**
     * Using Field Mutators to manage _Additional information about this object
     */
    public function getLocationAttribute() {         // used in Article level
        return (isset($this->additional['Location']) ? $this->additional['Location'] : "" );
    }
    public function setLocationAttribute($value) {
        $this->additional['Location'] = $value;
    }

	/*
	 * Query Scope, when query building, this type of method is used to build the common parts
	 * Function name format is
	 * 'scope' . capitalize(functionName)
	 * If elsewhere, (possibly our controller) we often have something like this
	 * InboundOrderDetail::where('Status', '=', 'NEW')->get();
	 * it can be replaced with InboundOrderDetail::isNew()->get();
	 * and we can write the scope function as
	 */
	public function scopeIsNew($query) {
		$query->where('Status', '=', 'NEW');
	}

	/**
	 * An InboundOrderDetail belongsTo an InboundOrder
	 * See: https://laracasts.com/series/laravel-5-fundamentals/episodes/14
	 * shows how to configure this when using database migrations.
	 * TODO learning, need to know how to configure Eloquent Relationships without database migrations.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function inboundOrder() {
		return $this->belongsTo('App/InboundOrder');
	}

}
