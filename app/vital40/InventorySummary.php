<?php namespace App\vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InventorySummary extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Inventory_Summary';
	/** primaryKey column */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment $primaryKey */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = true;

	/**
	 * Table Structure
	 * desc Inventory_Summary;
    +-------------+--------------+------+-----+---------------------+-------+
    | Field       | Type         | Null | Key | Default             | Extra |
    +-------------+--------------+------+-----+---------------------+-------+
    | objectID    | bigint(20)   | NO   | PRI | NULL                |       |
    | Client_SKU  | varchar(85)  | YES  |     | NULL                |       |
    | Description | varchar(255) | YES  |     | NULL                |       |
    | pickQty     | int(10)      | NO   |     | NULL                |       |
    | actQty      | int(10)      | NO   |     | NULL                |       |
    | resQty      | int(10)      | NO   |     | NULL                |       |
    | replenPrty  | int(10)      | YES  |     | NULL                |       |
    | created_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
    | updated_at  | timestamp    | NO   |     | 0000-00-00 00:00:00 |       |
    +-------------+--------------+------+-----+---------------------+-------+
    9 rows in set (0.03 sec)
     */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('objectID', 'Client_SKU', 'Description', 'pickQty', 'actQty', 'resQty', 'replenPrty');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('created_at', 'updated_at');

}
