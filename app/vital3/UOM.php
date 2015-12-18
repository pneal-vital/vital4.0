<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UOM extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'UOM';
	/** primaryKey column */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * select * from UOM;
    +------------+------+
    | objectID   | Uom  |
    +------------+------+
    | 6203039206 | DZ   |
    | 6203039213 | SKU  |
    | 6203039219 | EA   |
    | 6203040110 | ST   |
    | 6203060743 | CS   |
    +------------+------+
    5 rows in set (0.01 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('Uom');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('objectID');

    /**
     * This function can set objectID, set default values, and validate the entered field values.
     *
     * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
     * or call it from EventServiceProvider::boot(..)
     */
    public function isCreating() {
        // set objectID
        $inserted = VitalObject::create(['classID' => 'UOM']);
        $this->objectID = $inserted->objectID;
    }

}
