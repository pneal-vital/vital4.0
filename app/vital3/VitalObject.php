<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class VitalObject extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'object';
	/** primaryKey is objectID */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment objectID */
	public $incrementing = true;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc object;
	+----------+-------------+------+-----+---------+----------------+
	| Field    | Type        | Null | Key | Default | Extra          |
	+----------+-------------+------+-----+---------+----------------+
	| objectID | bigint(20)  | NO   | PRI | NULL    | auto_increment |
	| classID  | varchar(85) | NO   | MUL |         |                |
	+----------+-------------+------+-----+---------+----------------+
	2 rows in set (0.05 sec)
     * select distinct classID from object order by 1;
    +-----------------------+
    | classID               |
    +-----------------------+
    | Client                |
    | Generic_Container     |
    | Inbound_Order         |
    | Inbound_Order_Detail  |
    | Inventory             |
    | Item                  |
    | Label_Printer         |
    | Loads                 |
    | Location              |
    | Outbound_Order        |
    | Outbound_Order_Detail |
    | Pallet                |
    | Pick                  |
    | Pick_Detail           |
    | Printer               |
    | Region                |
    | Shipment              |
    | Ship_To               |
    | UOM                   |
    | Warehouse             |
    +-----------------------+
    20 rows in set (0.10 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('classID');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('objectID');

}
