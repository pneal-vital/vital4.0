<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GenericContainer extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Generic_Container';
	/** primaryKey column */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Generic_Container;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | NULL    |       |
    | Carton_ID | varchar(85) | YES  | MUL | NULL    |       | contains a LPN (example '52 0015 9955'), or => Generic_Container, Pallet or Pick
    | Status    | varchar(85) | YES  |     | OPEN    |       | values in ('OPEN', 'LOADED')
    +-----------+-------------+------+-----+---------+-------+
    3 rows in set (0.01 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('Carton_ID', 'Status');

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
        $inserted = VitalObject::create(['classID' => 'Generic_Container']);
        $this->objectID = $inserted->objectID;

        // set default values
        //$this->Status = "OPEN";

        /* validate the entered field values.
        if ( ! $this->isValid()) return false;
        */
    }

}
