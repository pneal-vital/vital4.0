<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Pallet extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Pallet';
	/** primaryKey column */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Pallet;
    +-----------+-------------+------+-----+---------+-------+
    | Field     | Type        | Null | Key | Default | Extra |
    +-----------+-------------+------+-----+---------+-------+
    | objectID  | bigint(20)  | NO   | PRI | 0       |       |
    | Pallet_ID | varchar(85) | NO   | MUL |         |       | contains names like INBOUND, or => Generic_Container, Inventory, Item, Label_Printer, Outbound_Order_Detail, Pallet, Pick, Pick_Detail, Shipment
    | x         | varchar(85) | NO   |     |         |       |
    | y         | varchar(85) | NO   |     |         |       |
    | z         | varchar(85) | NO   |     |         |       |
    | Status    | varchar(85) | NO   |     |         |       | in ('LOCK', 'OPEN', 'LOADED', 'SHIPPED')
    +-----------+-------------+------+-----+---------+-------+
    6 rows in set (0.04 sec)
     *
     * Once LOADED or SHIPPED, Pallet_ID = objectID or ''
     */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('Pallet_ID', 'x', 'y', 'z', 'Status');

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
        $inserted = VitalObject::create(['classID' => 'Pallet']);
        $this->objectID = $inserted->objectID;
        if(!isset($this->Pallet_ID) || strlen($this->Pallet_ID) == 0) {
            $this->Pallet_ID = $inserted->objectID;
        }

        // set default values
        //$this->Status = "OPEN";

        /* validate the entered field values.
        if ( ! $this->isValid()) return false;
        */
    }

}
