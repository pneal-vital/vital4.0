<?php namespace vital40;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ReceiptHistory extends Eloquent {

	/** Database connection to use */
	protected $connection = 'devaudit';
	/** Table to use */
	protected $table = 'Receipt_History';
	/** primaryKey is objectID */
	protected $primaryKey = 'activityID';
	/** Allow DB to increment PK */
	public $incrementing = false;

	/**
	 * Table Structure
	 * desc Receipt_History;
    +------------+---------------------+------+-----+---------------------+----------------+
    | Field      | Type                | Null | Key | Default             | Extra          |
    +------------+---------------------+------+-----+---------------------+----------------+
    | activityID | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
    | PO         | bigint(20)          | NO   |     | NULL                |                |
    | POD        | bigint(20)          | YES  |     | NULL                |                |
    | Article    | bigint(20)          | YES  |     | NULL                |                |
    | UPC        | bigint(20)          | YES  |     | NULL                |                |
    | Inventory  | bigint(20)          | YES  |     | NULL                |                |
    | Tote       | bigint(20)          | YES  |     | NULL                |                |
    | Cart       | bigint(20)          | YES  |     | NULL                |                |
    | Location   | bigint(20)          | YES  |     | NULL                |                |
    | User_Name  | varchar(85)         | NO   |     | NULL                |                |
    | created_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | updated_at | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
    | Activity   | text                | NO   |     | NULL                |                |
    +------------+---------------------+------+-----+---------------------+----------------+
    13 rows in set (0.00 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('PO', 'POD', 'Article', 'UPC', 'Inventory', 'Tote', 'Cart', 'Location', 'User_Name', 'Activity');

	/**
	 * Attributes not mass-assignable during inserts
	 * @var array
	 */
	protected $guarded = array('activityID', 'created_at', 'updated_at');

}
