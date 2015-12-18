<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Location extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Location';
	/** primaryKey column */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Location;
    +---------------+-------------+------+-----+---------+-------+
    | Field         | Type        | Null | Key | Default | Extra |
    +---------------+-------------+------+-----+---------+-------+
    | objectID      | bigint(20)  | NO   | PRI | NULL    |       |
    | Location_Name | varchar(85) | YES  | MUL | NULL    |       |
    | Capacity      | varchar(85) | YES  |     | NULL    |       | in ('', 1, 6, 999, 9999), set to 1
    | x             | varchar(85) | YES  |     | NULL    |       |
    | y             | varchar(85) | YES  |     | NULL    |       |
    | z             | varchar(85) | YES  |     | NULL    |       |
    | Status        | varchar(85) | YES  |     | NULL    |       | always 'OPEN'
    | LocType       | varchar(85) | YES  | MUL | NULL    |       | may be '', 'ACTIVITY', 'RESERVE', 'WORK', or 'PICK' + pick Sequence number
    | Comingle      | varchar(85) | YES  |     | NULL    |       | in ('N', 'P')
    | ChargeType    | varchar(85) | YES  |     | NULL    |       | in ('', 'N/A', 'NA'), set to ''
    +---------------+-------------+------+-----+---------+-------+
    10 rows in set (0.08 sec)
     *
     * Location_Name:
     * 'BA', 'BPROAM', 'BREAKPACK',
     * 'PACKING', 'PICKING', 'PICKSHORT',
     * 'Receiving', 'REPLEN', 'REWORK',
     * 'TZONE'
     * one of 'E-AA', 'E-AB', 'E-AC', 'E-AE', 'E-AF', 'E-BB', 'E-BC' + 4 digit number
     * one of 'S-' + 4 digit number + 'A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'B3', 'B4', 'C1', ..
     * one of 'W-AB', 'W-AC', 'W-AG', 'W-AH', 'W-AI', 'W-BA', 'W-BB', 'W-BC', 'W-CA' + 4 digit number
     */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('Location_Name', 'Capacity', 'x', 'y', 'z', 'LocType', 'Comingle');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('objectID', 'Status', 'ChargeType');


    /**
     * This function can set objectID, set default values, and validate the entered field values.
     *
     * Register this function in an Event Listener, see: http://laravel.com/docs/master/events
     * or call it from EventServiceProvider::boot(..)
     */
    public function isCreating() {
        // set objectID
        $inserted = VitalObject::create(['classID' => 'Location']);
        $this->objectID = $inserted->objectID;

        // set default values
        $this->Capacity = 1;
        $this->Status = "OPEN";
        $this->ChargeType = "";

        /* validate the entered field values.
        if ( ! $this->isValid()) return false;
        */
    }

}
