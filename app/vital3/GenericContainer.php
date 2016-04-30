<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GenericContainer extends Eloquent {

	const CONNECTION_NAME = 'vitaldev';
	const TABLE_NAME      = 'Generic_Container';
    const TABLE_SYNONYM   = 'Tote';

	/** Database connection to use */
	protected $connection = self::CONNECTION_NAME;
	/** Table to use */
	protected $table = self::TABLE_NAME;
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
        $inserted = VitalObject::create(['classID' => self::TABLE_NAME]);
        $this->objectID = $inserted->objectID;

        // set default values
        //$this->Status = "OPEN";

        /* validate the entered field values.
        if ( ! $this->isValid()) return false;
        */
    }

	/**
	 * This function is invoked before saving, (includes, create and update)
	 * See; EventServiceProvider::boot(..) ..::saving(..)
	 */
	public function isSaving() {
		$this->Carton_ID = trim($this->Carton_ID);
		$this->Status    = trim($this->Status   );
	}

}
