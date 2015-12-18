<?php namespace App\vital3;

use Illuminate\Database\Eloquent\Model as Eloquent;

class InboundOrderDetailAdditional extends Eloquent {

	/** Database connection to use */
	protected $connection = 'vitaldev';
	/** Table to use */
	protected $table = 'Inbound_Order_Detail_Additional';
	/** primaryKey is objectID, written here as objectid because objectID gets expanded to object_i_d */
	protected $primaryKey = 'objectID';
	/** Allow DB to increment objectID */
	public $incrementing = false;
	/** Does the table have laravel style timestamp fields */
	public $timestamps = false;

	/**
	 * Table Structure
	 * desc Inbound_Order_Detail_Additional;
	+----------+-------------+------+-----+---------+-------+
	| Field    | Type        | Null | Key | Default | Extra |
	+----------+-------------+------+-----+---------+-------+
	| objectID | bigint(20)  | NO   | PRI | NULL    |       |
	| Name     | varchar(85) | NO   | PRI | NULL    |       |
	| Value    | text        | NO   |     | NULL    |       |
	+----------+-------------+------+-----+---------+-------+
	3 rows in set (0.04 sec)
	 */

	/**
	 * Attributes that are mass-assignable during inserts
	 * @var array
	 */
	protected $fillable = array('objectID', 'Name', 'Value');

}
