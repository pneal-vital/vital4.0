<?php namespace vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class VendorCompliance extends Eloquent {

	/** Database connection to use */
	protected $connection = 'devaudit';
	/** Table to use */
	protected $table = 'Vendor_Compliance';
	/** primaryKey is objectID */
	protected $primaryKey = 'activityID';
	/** Allow DB to increment PK */
	public $incrementing = True;

	/**
	 * Table Structure
	 * desc desc Vendor_Compliance;
    +-------------+---------------------+------+-----+---------------------+----------------+
    | Field       | Type                | Null | Key | Default             | Extra          |
    +-------------+---------------------+------+-----+---------------------+----------------+
    | activityID  | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | vendorID    | bigint(20)          | NO   |     | NULL                |                |
    | poID        | bigint(20)          | NO   |     | NULL                |                |
    | podID       | bigint(20)          | NO   |     | NULL                |                |
    | articleID   | bigint(20)          | NO   |     | NULL                |                |
    | upcID       | bigint(20)          | NO   |     | NULL                |                |
    | expectedQty | int(11)             | NO   |     | NULL                |                |
    | receivedQty | int(11)             | NO   |     | NULL                |                |
    | created_at  | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at  | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    +-------------+---------------------+------+-----+---------------------+----------------+
    10 rows in set (0.01 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('poID', 'podID', 'articleID', 'upcID', 'expectedQty', 'receivedQty');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('activityID', 'created_at', 'updated_at');

}
