<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Pallet extends Eloquent {

    const CONNECTION_NAME = 'vitaldev';
    const TABLE_NAME      = 'Pallet';
    const TABLE_SYNONYM   = 'Cart';

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
        $inserted = VitalObject::create(['classID' => self::TABLE_NAME]);
        $this->objectID = $inserted->objectID;
        if(!isset($this->Pallet_ID) || strlen($this->Pallet_ID) == 0) {
            $this->Pallet_ID = $inserted->objectID;
        }

        // set default values
        $this->x = '1';
        $this->y = '1';
        $this->z = '1';

        /* validate the entered field values.
        if ( ! $this->isValid()) return false;
        */
    }

    /**
     * This function is invoked before saving, (includes, create and update)
     * See; EventServiceProvider::boot(..) ..::saving(..)
     */
    public function isSaving() {
        $this->Pallet_ID = trim($this->Pallet_ID);
        $this->x         = trim($this->x        );
        $this->y         = trim($this->y        );
        $this->z         = trim($this->z        );
        $this->Status    = trim($this->Status   );
    }

}
