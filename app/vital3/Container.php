<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Container extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'container';
	/** primaryKey column */
	protected $primaryKey = 'containerID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc container;
    +-------------+------------+------+-----+---------+----------------+
    | Field       | Type       | Null | Key | Default | Extra          |
    +-------------+------------+------+-----+---------+----------------+
    | containerID | bigint(20) | NO   | PRI | NULL    | auto_increment |
    | parentID    | bigint(20) | YES  | MUL | 0       |                | => Client, Generic_Container, Inbound_Order, Loads, Location, Pallet, Pick, Shipment, Warehouse
    | objectID    | bigint(20) | YES  | MUL | 0       |                | => Generic_Container, Inbound_Order, Inbound_Order_Detail, Inventory, Loads, Location, Pallet, Pick, Pick_Detail, Shipment
    | classID     | bigint(20) | YES  | MUL | 0       |                | always '0'
    +-------------+------------+------+-----+---------+----------------+
    4 rows in set (0.00 sec)

     * Container (class of parentID) => Contained (class of objectID)
     * select distinct parent.classID as Container, child.classID as Contained  from container
         join object parent on parent.objectID = container.parentID
         join object child on child.objectID = container.objectID
        order by 1,2;
    +-------------------+----------------------+
    | Container         | Contained            |
    +-------------------+----------------------+
    | Client            | Inbound_Order        |
    | Generic_Container | Inventory            |
    | Generic_Container | Pick                 |
    | Inbound_Order     | Inbound_Order_Detail |
    | Loads             | Shipment             |
    | Location          | Pallet               |
    | Pallet            | Generic_Container    |
    | Pallet            | Inventory            |
    | Pallet            | Pallet               |
    | Pick              | Pick_Detail          |
    | Shipment          | Pallet               |
    | Warehouse         | Loads                |
    | Warehouse         | Location             |
    | Warehouse         | Pallet               |
    +-------------------+----------------------+
    14 rows in set (37.93 sec)

	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('parentID', 'objectID');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('containerID', 'classID');

}
