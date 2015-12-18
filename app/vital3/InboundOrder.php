<?php namespace App\vital3;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

class InboundOrder extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Inbound_Order';
	/** primaryKey is objectID */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment objectID */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;
	/** created at column. */
	const CREATED_AT = 'Created';
	/** Attributes that are dates. */
	protected $dates = ['Created', 'Expected'];

	/**
	 * Table Structure
	 * desc Inbound_Order;
	+----------------------+-------------+------+-----+---------+-------+
	| Field                | Type        | Null | Key | Default | Extra |
	+----------------------+-------------+------+-----+---------+-------+
	| objectID             | bigint(20)  | NO   | PRI | NULL    |       |
	| Order_Number         | varchar(85) | YES  |     | NULL    |       |
	| Client               | varchar(85) | YES  |     | NULL    |       |
	| Purchase_Order       | varchar(85) | YES  | MUL | NULL    |       |
	| Invoice_Number       | varchar(85) | YES  |     | NULL    |       |
	| Special_Instructions | varchar(85) | YES  |     | NULL    |       | always ""
	| Status               | varchar(85) | YES  | MUL | NULL    |       |
	| Created              | varchar(85) | YES  |     | NULL    |       |
	| Expected             | varchar(85) | YES  |     | NULL    |       |
	| Actual               | varchar(85) | YES  |     | NULL    |       | always ""
	| vendorID             | bigint(20)  | YES  | MUL | NULL    |       | always 0
	+----------------------+-------------+------+-----+---------+-------+
	11 rows in set (0.03 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('Order_Number', 'Client', 'Purchase_Order', 'Invoice_Number', 'Status', 'Expected');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('objectID', 'Created');

	/*
	 * Attributes that should be hidden. In this case these fields are not used, always "".
	 */
	protected $hidden = array('Special_Instructions', 'Actual', 'VendorID');


	/**
	 * This function can set objectID, set default values, and validate the entered field values.
	 *
	 * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
	 * or call it from EventServiceProvider::boot(..)
	 */
	public function isCreating() {
		// set objectID
		$inserted = VitalObject::create(['classID' => 'Inbound_Order']);
		$this->objectID = $inserted->objectID;

		// set default values
		$this->Special_Instructions = "";
		$this->Status = "NEW";
		$this->Created = Carbon::now();
		$this->Actual = "";
		$this->VendorID = 0;

		/* validate the entered field values.
		if ( ! $this->isValid()) return false;
		*/
	}


	/*
	 * Field Mutators, used to validate, manipulate the value on set
	 * TODO Add get/set Client Attribute(..) - retrieve the objectID from Client
	 * TODO Add get/set Status Attribute(..) - value must be valid, see STD.
	 * Function name format is
	 * 'set' . CamileCase(fieldName) . 'Attribute'
	 * as in setNameAttribute() { .. }
	 */
	public function getExpectedAttribute()
	{
		$result = Carbon::create(1901,01,01,00,00,00);
		if(isset($this->attributes['Expected']) && strlen($this->attributes['Expected']) > 14) {
			$result = Carbon::createFromFormat('Ymd His', $this->attributes['Expected']);
		}
		return $result;
	}

	public function setExpectedAttribute($value)
	{
		// Consider using Carbon::parse($value);
		$expected = Carbon::tomorrow();
		if(isset($value) && strlen($value) == 10) {
			$expected = Carbon::createFromFormat('Y-m-d', $value)->format("Ymd His");
		} elseif(isset($value) && strlen($value) == 19) {
			$expected = Carbon::createFromFormat('Y-m-d  H:i:s', $value)->format("Ymd His");
		}
		$this->attributes['Expected'] = $expected;
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

	/**
	 * An InboundOrder hasMay InboundOrderDetails.
	 * See: https://laracasts.com/series/laravel-5-fundamentals/episodes/14
	 * shows how to configure this when using database migrations.
	 * TODO learning, need to know how to configure this without database migrations.
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function inboundOrderDetails() {
		return $this->hasMany('App\InboundOrderDetail');
	}
}
